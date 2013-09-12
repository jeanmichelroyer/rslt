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

class rsltTpl
{
    // albums
    public static function Albums($attr, $content) {
        $res = '';
        
        $res .= "<?php\n";
        $res .= '$_ctx->albums = $_ctx->album_manager->getList();';
        $res .= 'while ($_ctx->albums->fetch()):?>';
        $res .= $content;
        $res .= '<?php endwhile; $_ctx->albums = null;?>';
        
        return $res;
    }

    public static function AlbumTitle($attr) {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        
        return '<?php echo '.sprintf($f, '$_ctx->albums->title').'; ?>';
    }

   public static function AlbumURL($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);

       return '<?php echo '.sprintf($f,'$core->blog->url.$core->url->getBase("album").'.
       '"/".rawurlencode($_ctx->albums->url)').'; ?>';
   }

   // album
   public static function AlbumPageTitle($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->album->title').'; ?>';
   }

   public static function AlbumPageSinger($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->album->singer').'; ?>';
   }   

   public static function AlbumPagePublicationDate($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, 'dt::dt2str("%Y", $_ctx->album->publication_date)').'; ?>';
   }   

   public static function AlbumSongs($attr, $content) {
        $res = '';
        
        $res .= "<?php\n";
        $res .= '$_ctx->songs = $_ctx->album_manager->getSongs($_ctx->album->id);';
        $res .= 'while ($_ctx->songs->fetch()):?>';
        $res .= $content;
        $res .= '<?php endwhile; $_ctx->songs = null;?>';
        
        return $res;
    }

   public static function SongTitle($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->songs->title').'; ?>';
   }   

   public static function SongAuthor($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->songs->author').'; ?>';
   }   

}