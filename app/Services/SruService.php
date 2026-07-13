<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class SruService
{
    /**
     * Search books from an SRU server.
     *
     * @param string $query Keywords (title, author, isbn)
     * @param string $serverUrl SRU Server Base URL
     * @return array List of parsed books
     */
    public function search($query, $serverUrl = 'http://z3950.loc.gov:7090/voyager?')
    {
        // Construct the SRU URL with standard query parameters
        // query=cql.anywhere="query" or similar. Standard CQL format.
        $cql = 'title="' . $query . '" or creator="' . $query . '" or isbn="' . $query . '"';
        
        try {
            $response = Http::timeout(10)->get($serverUrl, [
                'operation' => 'searchRetrieve',
                'version' => '1.1',
                'query' => $cql,
                'maximumRecords' => 10,
                'recordSchema' => 'marcxml',
            ]);

            if ($response->failed()) {
                return [];
            }

            return $this->parseMarcXml($response->body());
        } catch (\Exception $e) {
            // Log or fallback
            return [];
        }
    }

    /**
     * Parse MARCXML response payload into structured book details.
     */
    protected function parseMarcXml($xmlString)
    {
        $books = [];
        
        try {
            // Clean namespace prefixes to make XPath parsing easier
            $xmlString = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$3', $xmlString);
            $xml = new SimpleXMLElement($xmlString);

            // Register standard record elements
            $records = $xml->xpath('//record');

            foreach ($records as $index => $record) {
                $book = [
                    'id' => $index,
                    'title' => '',
                    'author' => '',
                    'isbn' => '',
                    'publisher' => '',
                    'publish_year' => '',
                    'publish_place' => '',
                    'classification' => '',
                    'edition' => '',
                ];

                // Parse MARC datafields
                $datafields = $record->xpath('.//datafield');
                foreach ($datafields as $field) {
                    $tag = (string)$field['tag'];

                    // 1. Title (245 a)
                    if ($tag === '245') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $sub_b = $field->xpath('.//subfield[@code="b"]');
                        $book['title'] = trim((string)($sub_a[0] ?? '')) . ' ' . trim((string)($sub_b[0] ?? ''));
                        $book['title'] = rtrim(trim($book['title']), ' /:');
                    }

                    // 2. Author (100 a or 700 a)
                    if ($tag === '100' || $tag === '700') {
                        if (empty($book['author'])) {
                            $sub_a = $field->xpath('.//subfield[@code="a"]');
                            $book['author'] = rtrim(trim((string)($sub_a[0] ?? '')), ',');
                        }
                    }

                    // 3. ISBN (020 a)
                    if ($tag === '020') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $rawIsbn = (string)($sub_a[0] ?? '');
                        // Extract only numeric/X part of ISBN
                        preg_match('/[0-9xX]+/', $rawIsbn, $matches);
                        $book['isbn'] = $matches[0] ?? '';
                    }

                    // 4. Publisher / Publication details (260 or 264)
                    if ($tag === '260' || $tag === '264') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]'); // Place
                        $sub_b = $field->xpath('.//subfield[@code="b"]'); // Publisher name
                        $sub_c = $field->xpath('.//subfield[@code="c"]'); // Year

                        $book['publish_place'] = rtrim(trim((string)($sub_a[0] ?? '')), ' :;,');
                        $book['publisher'] = rtrim(trim((string)($sub_b[0] ?? '')), ' :;,');
                        $book['publish_year'] = preg_replace('/[^0-9]/', '', (string)($sub_c[0] ?? ''));
                    }

                    // 5. Classification DDC (082 a)
                    if ($tag === '082') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $book['classification'] = trim((string)($sub_a[0] ?? ''));
                    }

                    // 6. Edition (250 a)
                    if ($tag === '250') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $book['edition'] = trim((string)($sub_a[0] ?? ''));
                    }
                }

                if (!empty($book['title'])) {
                    $books[] = $book;
                }
            }
        } catch (\Exception $e) {
            // Return empty list on parse failure
        }

        return $books;
    }
}
