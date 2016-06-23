#!/usr/bin/env php5

<?PHP
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the Cooperative Library Network Berlin-Brandenburg,
 * the Saarland University and State Library, the Saxon State Library -
 * Dresden State and University Library, the Bielefeld University Library and
 * the University Library of Hamburg University of Technology with funding from
 * the German Research Foundation and the European Regional Development Fund.
 *
 * LICENCE
 * OPUS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * @category    Application
 * @author      Jens Schwidder <schwidder@zib.de>
 * @copyright   Copyright (c) 2016, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 */

/*
 * This script updates multiple documents based on a list of IDs.
 *
 * Parameters:
 *
 * i - Input file with IDs
 * t - Identifier type (e.g. 'urn')
 * f - field to update
 * v - new value
 */

require_once dirname(__FILE__) . '/bootstrap.php';


// TODO processing input parameters
// TODO make it a class

// Read identifiers

class OpusUpdateTool {

    public function run()
    {
        $file = new SplFileObject(APPLICATION_PATH . '/tests/resources/urns.list');

        $identifiers = array();

        while (!$file->eof()) {
            $value = trim($file->fgets());
            $identifiers[] = $value;
        }

        $documentIds = array();

        // get document ids

        foreach ($identifiers as $value) {
            $finder = new Opus_DocumentFinder();
            $finder->setIdentifierTypeValue('urn', $value);
            $docIds = $finder->ids();
            $documentIds = array_merge($documentIds, $docIds);
        }

        // update documents

        foreach ($documentIds as $docId) {
            $doc = new Opus_Document($docId);

            $field = $doc->getField('BelongsToBibliography');

            $field->setValue(0);

            $doc->store();
        }
    }

}

$shortOpts = "";
$shortOpts .= "i:";
$shortOpts .= "t:";
$shortOpts .= "f:";
$shortOpts .= "v:";

// $longOpts = "";

$options = getopt($shortOpts);

if (!$options) {
    // Error reading parameters (e.g. missing option)
}
else {
    var_dump($options);

    $tool = new OpusUpdateTool();
    // $tool->run();
}