<?php 
    date_default_timezone_set('Asia/Tokyo');
     
    $url = "https://nkiib.wordpress.com/feed";
    $rss =  simplexml_load_file($url);
    $output = '';
    $i = 0;
    // 取得件数
    $max = 5;
    if($rss){
        foreach( $rss->channel->item as $item )
        {
            /**
            * タイトル
            * $item->title ;
            * リンク
            * $item->link ;
            * 更新日時のUNIX TIMESTAMP
            * $timestamp = strtotime( $item->pubDate ) ;
            * 詳細
            * $item->description;
             */
            if(!preg_match('/^PR:/',$item->title )){
                if($i < $max){
                    $timestamp = strtotime( $item->pubDate );
                    $date = date( 'Y.m.d',$timestamp );
                    $output .= '<dt>';
                    $output .= '<time datetime="' . $item->pubDate . '">' . $date . '</time>';
                    $output .= '</dt>';
$output .= '<dd>';
$output .= '<a href="'. $item->link .'" target="_blank">' . $item->title . '</a>';
                    $output .= '</dd>';
                    $i++;
                }
            }
            
        }
    }
     
    echo '<dl class="list">'. $output . '</dl>';
?>