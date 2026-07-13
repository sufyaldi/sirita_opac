<?php

namespace App\Services;

use App\Models\Biblio;
use SimpleXMLElement;

class MarcService
{
    /**
     * Parse uploaded MARCXML file.
     *
     * @param string $xmlContent Content of the uploaded MARCXML file
     * @return array List of parsed books
     */
    public function parseMarcXml($xmlContent)
    {
        $books = [];
        
        try {
            // Clean namespace prefixes to make XPath parsing easier
            $xmlContent = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$3', $xmlContent);
            $xml = new SimpleXMLElement($xmlContent);

            // Register standard record elements
            $records = $xml->xpath('//record');
            if (empty($records)) {
                // Try direct child record tags
                $records = $xml->xpath('record');
            }

            foreach ($records as $index => $record) {
                $book = [
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

                    // Title
                    if ($tag === '245') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $sub_b = $field->xpath('.//subfield[@code="b"]');
                        $book['title'] = trim((string)($sub_a[0] ?? '')) . ' ' . trim((string)($sub_b[0] ?? ''));
                        $book['title'] = rtrim(trim($book['title']), ' /:');
                    }

                    // Author
                    if ($tag === '100' || $tag === '700') {
                        if (empty($book['author'])) {
                            $sub_a = $field->xpath('.//subfield[@code="a"]');
                            $book['author'] = rtrim(trim((string)($sub_a[0] ?? '')), ',');
                        }
                    }

                    // ISBN
                    if ($tag === '020') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $rawIsbn = (string)($sub_a[0] ?? '');
                        preg_match('/[0-9xX]+/', $rawIsbn, $matches);
                        $book['isbn'] = $matches[0] ?? '';
                    }

                    // Publisher
                    if ($tag === '260' || $tag === '264') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]'); // Place
                        $sub_b = $field->xpath('.//subfield[@code="b"]'); // Publisher
                        $sub_c = $field->xpath('.//subfield[@code="c"]'); // Year

                        $book['publish_place'] = rtrim(trim((string)($sub_a[0] ?? '')), ' :;,');
                        $book['publisher'] = rtrim(trim((string)($sub_b[0] ?? '')), ' :;,');
                        $book['publish_year'] = preg_replace('/[^0-9]/', '', (string)($sub_c[0] ?? ''));
                    }

                    // Classification
                    if ($tag === '082') {
                        $sub_a = $field->xpath('.//subfield[@code="a"]');
                        $book['classification'] = trim((string)($sub_a[0] ?? ''));
                    }

                    // Edition
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
            // Failed to parse XML
        }

        return $books;
    }

    /**
     * Generate MARCXML output from a list of Biblio records.
     *
     * @param \Illuminate\Database\Eloquent\Collection $books
     * @return string MARCXML representation
     */
    public function generateMarcXml($books)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><collection xmlns="http://www.loc.gov/MARC21/slim"/>');

        foreach ($books as $book) {
            $record = $xml->addChild('record');
            $record->addChild('leader', '00000nam a2200000   4500');

            // 1. ISBN (020)
            if (!empty($book->isbn_issn)) {
                $df = $record->addChild('datafield');
                $df->addAttribute('tag', '020');
                $df->addAttribute('ind1', ' ');
                $df->addAttribute('ind2', ' ');
                $sub = $df->addChild('subfield', htmlspecialchars($book->isbn_issn));
                $sub->addAttribute('code', 'a');
            }

            // 2. Author (100)
            $firstAuthor = $book->authors->first();
            if ($firstAuthor) {
                $df = $record->addChild('datafield');
                $df->addAttribute('tag', '100');
                $df->addAttribute('ind1', '1');
                $df->addAttribute('ind2', ' ');
                $sub = $df->addChild('subfield', htmlspecialchars($firstAuthor->author_name));
                $sub->addAttribute('code', 'a');
            }

            // 3. Title (245)
            $df = $record->addChild('datafield');
            $df->addAttribute('tag', '245');
            $df->addAttribute('ind1', '1');
            $df->addAttribute('ind2', '0');
            $sub = $df->addChild('subfield', htmlspecialchars($book->title));
            $sub->addAttribute('code', 'a');

            // 4. Edition (250)
            if (!empty($book->edition)) {
                $df = $record->addChild('datafield');
                $df->addAttribute('tag', '250');
                $df->addAttribute('ind1', ' ');
                $df->addAttribute('ind2', ' ');
                $sub = $df->addChild('subfield', htmlspecialchars($book->edition));
                $sub->addAttribute('code', 'a');
            }

            // 5. Publisher / Publication Info (260)
            $publisherName = $book->publisher->publisher_name ?? '';
            $placeName = $book->publishPlace->place_name ?? '';
            $year = $book->publish_year ?? '';

            if (!empty($publisherName) || !empty($placeName) || !empty($year)) {
                $df = $record->addChild('datafield');
                $df->addAttribute('tag', '260');
                $df->addAttribute('ind1', ' ');
                $df->addAttribute('ind2', ' ');
                
                if (!empty($placeName)) {
                    $sub = $df->addChild('subfield', htmlspecialchars($placeName));
                    $sub->addAttribute('code', 'a');
                }
                if (!empty($publisherName)) {
                    $sub = $df->addChild('subfield', htmlspecialchars($publisherName));
                    $sub->addAttribute('code', 'b');
                }
                if (!empty($year)) {
                    $sub = $df->addChild('subfield', htmlspecialchars($year));
                    $sub->addAttribute('code', 'c');
                }
            }

            // 6. Classification (082)
            if (!empty($book->classification)) {
                $df = $record->addChild('datafield');
                $df->addAttribute('tag', '082');
                $df->addAttribute('ind1', '0');
                $df->addAttribute('ind2', '4');
                $sub = $df->addChild('subfield', htmlspecialchars($book->classification));
                $sub->addAttribute('code', 'a');
            }
        }

        // Return formatted XML output
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }
}
