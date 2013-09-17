<?php
// +-----------------------------------------------------------------------+
// | RSLT - a plugin for dotclear                                          |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2013 Nicolas Roudaire             http://www.nikrou.net  |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License version 2 as     |
// | published by the Free Software Foundation                             |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,            |
// | MA 02110-1301 USA.                                                    |
// +-----------------------------------------------------------------------+

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_title = 'Load csv file';

if (!empty($_POST['file']) && !empty($_POST['object'])) {
    $filename = sprintf('%s/%s.csv', __DIR__.'/../../data', $_POST['file']);
    if (file_exists($filename)) {
        $song_manager = new songManager($core);
        $album_manager = new albumManager($core);
        $album_song = new albumSong($core);
        $reference_song = new referenceSong($core);

        if (($fh = fopen($filename, 'r')) !== false) {
            if ($_POST['object']=='song') {
                // "Année de publication";"Titre";"Auteur";"Compositeur";"Adaptateur";"Interprète";"Editeur";"Titre original"
                while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                    if (empty($data)) {
                        continue;
                    }
                    $publication_date = trim($data[0]);
                    $title = trim($data[1]);
                    $author = trim($data[2]);
                    $compositor = trim($data[3]);
                    $adaptator = trim($data[4]);
                    $singer = trim($data[5]);
                    $editor = trim($data[6]);
                    $original_title = trim($data[7]);

                    $song = $song_manager->replaceByTitleAndPublicationDate(array('publication_date' => $publication_date, 'title' => $title,
                    'author' => $author, 'compositor' => $compositor, 'adaptator' => $adaptator, 'singer' => $singer, 
                    'editor' => $editor, 'original_title' => $original_title));

                    // find known authors
                    if ((strpos($author, ',')!==false) && preg_match_all('`('.implode('|', $Authors).')`', $author, $matches)) {
                        foreach ($matches[0] as $author_title) {
                            $reference_song->add(array_search($author_title, $Authors), $song->id, 'author');
                        }
                    }

                    // find known compositors
                    if ((strpos($compositor, ',')!==false) && preg_match_all('`('.implode('|', $Authors).')`', $compositor, $matches)) {
                        foreach ($matches[0] as $author_title) {
                            $reference_song->add(array_search($author_title, $Authors), $song->id, 'compositor');
                        }
                    }

                    // find known adaptators
                    if ((strpos($adaptator, ',')!==false) && preg_match_all('`('.implode('|', $Authors).')`', $adaptator, $matches)) {
                        foreach ($matches[0] as $author_title) {
                            $reference_song->add(array_search($author_title, $Authors), $song->id, 'adaptator');
                        }
                    }
                }
                fclose($fh);
            } elseif ($_POST['object']=='album') {
                // "Année de publication";"Titre";"Interprète"
                while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                    if (empty($data)) {
                        continue;
                    }
                    $publication_date = trim($data[0]);
                    $title = trim($data[1]);
                    $singer = trim($data[2]);

                    if (empty($publication_date) || empty($title) || empty($singer)) {
                        continue;
                    }

                    $album_manager->replaceByTitle(array('publication_date' => $publication_date, 'title' => $title, 'singer' => $singer));
                }
                fclose($fh);
            } elseif ($_POST['object']=='album_song') {
                while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                    if (empty($data)) {
                        continue;
                    }
                }                
                fclose($fh);
            }
            
            $_SESSION['rslt_message'] = __('Data successfully loaded.');
            $_SESSION['rslt_default_tab'] = 'maintenance';
            http::redirect($p_url);
        }
    }
}

$default_tab = 'maintenance';

include(dirname(__FILE__).'/../views/load.tpl');

