<?php
/**
 * Create image for post title, author name, avatar and tag
 */
require_once 'ParameterInterface.php';
require_once 'ImageInCircle.php';
class TextImage extends ImageInCircle implements ParameterInterface { // return image

    public $text;
    public $author;
    public $avatar;
    public $tag;
    public $defaultTag;

    function __construct($text, $author, $avatar, $tag='', $defaultTag = 0) { // get the parameters to show in image
        $this->text = html_entity_decode($text);
        $this->author = $author;
        $this->avatar = $avatar;
        $this->tag = $tag;
        $this->defaultTag = $defaultTag;
    }

    function getData() {

        header('Content-type: image/png');
        $img_width = 600; // width of image
        $img_height = 250; // default height of image
        $font1 = plugin_dir_path( __FILE__ ) . "font/Noto_Sans_JP/noto-sans-jp-v36-latin-700.woff";  // font family
        $font2 = plugin_dir_path( __FILE__ ) . "font/Helvetica.ttf";  // font family
        $font3 = plugin_dir_path( __FILE__ ) . "font/SansSerif.ttf";  // font family
        $font4 = plugin_dir_path( __FILE__ ) . "font/arial.ttf";  // font family
        $font5 = plugin_dir_path( __FILE__ ) . "font/Noto_Sans_JP/noto-sans-jp-v36-latin-300.woff";  // font family
        $tagBackgroundImage = plugin_dir_path( __FILE__ ) . "img/tag.jpg"; // get tag background image

        $fontSize = 20;   // font size
        $fontSizeAuthor = 12; // font size author name
        $fontSizeTag = 13; // font size for tag
        // take the first n words from string and reduce string size
        $reduce = implode(' ', array_slice(explode(' ', $this->text), 0, 13));
        if(str_word_count($this->text) >= 14) {
            $reduce = $reduce.'...';
        }
        

        // create the outer image to contain elements
        $im = imagecreatetruecolor($img_width, $img_height);
        $white = imagecolorallocate($im, 255, 255, 255); 
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, $img_width, $img_height, $white);

        // dynamic width for the tag image based on the tag string size
        $tag_name = "";

        if($this->defaultTag != 0) {
            $tag_name = $this->tag;
        } else {
            if (strpos($this->tag, ' ') !== false) {
                $tag = get_term_by('name', $this->tag, 'category');
                if(empty($tag)) {
                    $tag = get_term_by('name', $this->tag, 'tag');
                    if(empty($tag)) {
                        $tag = get_term_by('name', $this->tag, 'post_tag');
                    }
                }
                $tag_name =  html_entity_decode($tag->name);
            } else {
                $tag = get_term_by('slug', $this->tag, 'category');
                if(empty($tag)) {
                    $tag = get_term_by('slug', $this->tag, 'tag');
                    if(empty($tag)) {
                        $tag = get_term_by('slug', $this->tag, 'post_tag');
                    }
                }
                $tag_name =  html_entity_decode($tag->name);
            }
        }

        //$tag_name =  $this->tag; 
        if(!empty($tag_name)) {
            $type_space_tag = imagettfbbox($fontSizeTag, 0, $font1, $tag_name);
            $box_width_tag = abs($type_space_tag[4] - $type_space_tag[0]) + 10;
            
            // add tag background image
            $imtagbackground = imagecreatefromjpeg($tagBackgroundImage);
            imagecopyresampled($im,$imtagbackground,3,30,0,0,$box_width_tag+29,32,270,60);
            
            // add tag into the image
            imagettftext($im, $fontSizeTag, 0, 23, 55, $white, $font1, $tag_name);
        }
        
        // add title into the image
        $text = $reduce;
        if(empty($tag_name)) {
            $drawFrame=array(10,5,$img_width,$img_height);
            $yaxis = 60;
        } else {
            $drawFrame=array(10,65,$img_width,$img_height);
            $yaxis = 110;
        }
        
        $fontType = plugin_dir_path( __FILE__ ) . "font/Noto_Sans_JP/noto-sans-jp-v36-latin-700.woff";
        $lineHeight=32;
        $wordSpacing=' ';
        $hAlign=-1; // -1:left  0:center 1:right
        $vAlign=-1; // -1:top  0:middle 1:bottom
        $ht = $this->wrapimagettftext($im, $fontSize, $drawFrame, $black,$fontType, $text, '100%',' ',$hAlign,$vAlign);
        
        // add author name into the image
        if(!empty($tag_name)) {
            imagettftext($im, $fontSizeAuthor, 0, 60, $ht+95, $black, $font5, $this->author);
        } else {
            imagettftext($im, $fontSizeAuthor, 0, 60, $ht+65, $black, $font5, $this->author);
        }

        // add avatar into the image
        $imgInCircle = new ImageInCircle(); // change image into circled image
        $im2 = $imgInCircle->circle($this->avatar);
        if(!empty($tag_name)) {
            imagecopyresampled($im,$im2,10,$ht+70,0,0,35,35,450,450);
        } else {
            imagecopyresampled($im,$im2,10,$ht+40,0,0,35,35,450,450);
        }
        

        imagepng($im);
        imagedestroy($im);
        
    }

    function wrapimagettftext($img, $fontSize, $drawFrame, $textColor,$fontType, $text, $lineHeight='',$wordSpacing='',$hAlign=0,$vAlign=0) {

        if($wordSpacing===' ' || $wordSpacing==='') {
            $size = imagettfbbox($fontSize, 0, $fontType, ' ');
            $wordSpacing=abs($size[4]-$size[0]);
        }
        $size = imagettfbbox($fontSize, 0, $fontType, 'Zltfgyjp');
        $baseHeight=abs($size[5]-$size[1]);
        $size = imagettfbbox($fontSize, 0, $fontType, 'Zltf');
        $topHeight=abs($size[5]-$size[1]);
    
        if($lineHeight==='' || $lineHeight==='') {
            $lineHeight=$baseHeight*110/100;
        } else if(is_string($lineHeight) && $lineHeight{strlen($lineHeight)-1}==='%') {
            $lineHeight=20+floatVal(substr($lineHeight,0,-1));
            $lineHeight=$baseHeight*$lineHeight/100;
        } else {
    
        }
    
        $usableWidth=$drawFrame[2]-$drawFrame[0];
        $usableHeight=$drawFrame[3]-$drawFrame[1];
    
        $leftX=$drawFrame[0];
        $centerX=$drawFrame[0]+$usableWidth/2;
        $rightX=$drawFrame[0]+$usableWidth;
    
        $topY=$drawFrame[1];
        $centerY=$drawFrame[1]+$usableHeight/2;
        $bottomY=$drawFrame[1]+$usableHeight;
    
        $text = explode(" ", $text);
    
        $line_w=-$wordSpacing;
        $line_h=0;
        $total_w=0;
        $total_h=0;
        $total_lines=0;
    
        $toWrite=array();
        $pendingLastLine=array();
    
        
        for($i=0;$i<count($text);$i++) {
            $size = imagettfbbox($fontSize, 0, $fontType, $text[$i]);
    
            $width = abs($size[4] - $size[0]);
            $height = abs($size[5] - $size[1]);
    
            $x = -$size[0]-$width/2;
            $y = $size[1]+$height/2;
    
            if($line_w+$wordSpacing+$width>$usableWidth) {
                $lastLineW=$line_w;
                $lastLineH=$line_h;
    
                if($total_w<$lastLineW) $total_w=$lastLineW;
                $total_h+=$lineHeight;
    
                foreach($pendingLastLine as $aPendingWord) {
    
                    if($hAlign<0) $tx=$leftX+$aPendingWord['tx'];
                    else if($hAlign>0) $tx=$rightX-$lastLineW+$aPendingWord['tx'];
                    else if($hAlign==0) $tx=$centerX-$lastLineW/2+$aPendingWord['tx'];
    
                    $toWrite[]=array('line'=>$total_lines,'x'=>$tx,'y'=>$total_h,'txt'=>$aPendingWord['txt']);
                }
                $pendingLastLine=array();
    
                $total_lines++;
                $line_w=$width;
                $line_h=$height;
    
                $pendingLastLine[]=array('tx'=>0,'w'=>$width,'h'=>$height,'x'=>$x,'y'=>$y,'txt'=>$text[$i]);
            } else {
    
                $line_w+=$wordSpacing;
                $pendingLastLine[]=array('tx'=>$line_w,'h'=>$width,'w'=>$height,'x'=>$x,'y'=>$y,'txt'=>$text[$i]);
                $line_w+=$width;
                if($line_h<$height) $line_h=$height;
            }
        }
    
        $lastLineW=$line_w;
        $lastLineH=$line_h;
    
        if($total_w<$lastLineW) $total_w=$lastLineW;
        $total_h+=$lineHeight;
    
        foreach($pendingLastLine as $aPendingWord) {
    
            if($hAlign<0) $tx=$leftX+$aPendingWord['tx'];
            else if($hAlign>0) $tx=$rightX-$lastLineW+$aPendingWord['tx'];
            else if($hAlign==0) $tx=$centerX-$lastLineW/2+$aPendingWord['tx'];
    
            $toWrite[]=array('line'=>$total_lines,'x'=>$tx,'y'=>$total_h,'txt'=>$aPendingWord['txt']);
        }
        $pendingLastLine=array();
        $total_lines++;
    
        $total_h+=$lineHeight-$topHeight;
    
        foreach($toWrite as $aWord) {
    
            $posx = $aWord['x'];
    
            if($vAlign<0) $posy=$topY+$aWord['y'];
            else if($vAlign>0) $posy=$bottomY-$total_h+$aWord['y'];
            else if($vAlign==0) $posy=$centerY-$total_h/2+$aWord['y'];
    
            imagettftext($img, $fontSize, 0, $posx, $posy , $textColor, $fontType, $aWord['txt']);
    
        }

        return $total_h;
    }
}

?>