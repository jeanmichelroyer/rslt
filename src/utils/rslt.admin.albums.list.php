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

class adminAlbumsList extends adminGenericList
{
    public static $anchor = 'albums';
    private $p_url;

    public function setPluginUrl($p_url) {
        $this->p_url = $p_url;
    }

    public function display($albums, $nb_per_page) {
        $pager = new rsltPager($albums, $this->rs_count, $nb_per_page, 10);
        $pager->setAnchor(self::$anchor);
        $pager->html_prev = __('&#171;prev.');
        $pager->html_next = __('next&#187;');

        $html_block = 
            '<table class="albums clear" id="albums-list">'.
            '<thead>'.
            '<tr>'.
            '<th>&nbsp;</th>'.
            '<th>'. __('Title').'</th>'.
            '<th>'.__('Singer').'</th>'.
            '<th class="nowrap">'.__('Publication date').'</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>%s</tbody></table>';
        
        echo '<p class="pagination">'.__('Page(s)').' : '.$pager->getLinks().'</p>';

        $blocks = explode('%s',$html_block);
        
        echo $blocks[0];
        
        while ($this->rs->fetch()) {
            echo $this->postLine();
        }

        echo $blocks[1];

        echo '<p class="pagination">'.__('Page(s)').' : '.$pager->getLinks().'</p>';
    }

    private function postLine() {
        $res = 
            '<tr>'.
            '<td>'.
            form::checkbox(array('albums[]'), $this->rs->id, '', '', '').
            '</td>'.
            '<td class="maximal">'.
            '<a href="'.sprintf($this->p_url, $this->rs->id).'">'.
            html::escapeHTML(text::cutString($this->rs->title, 50)).
            '</a>'.
            '</td>'.
            '<td class="nowrap">'.html::escapeHTML(text::cutString($this->rs->singer,50)).'</td>'.
            '<td class="nowrap">'.$this->rs->publication_date.'</td>'.
            '</tr>';
        
        return $res;
    }
}