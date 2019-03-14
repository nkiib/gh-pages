<?php
 
class AjaxFeed
{
 
    protected $feed = null;
 
    public function __construct()
    {
        // フィード取得
        $this->feed = $this->getFeed($_POST['url']);
        $result = $this->parseFeed();
 
        // JSONで返す
        $this->sendJson($result);
    }
 
    protected function getFeed($url)
    {
        // フィードを取得する
        $res = file_get_contents($url, false, stream_context_create($this->buildOptions()));
        // オブジェクトに変換する
        // CDATAを取得できるようにLIBXML_NOCDATAを指定する
        $feedData = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
 
        return $feedData;
    }
 
    protected function buildOptions()
    {
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => implode("\r\n", array(
                    'Content-Type: application/x-www-form-urlencoded'
                ))
            )
        );
 
        return $options;
    }
 
    protected function parseFeed()
    {
        $items = $this->getItems();
 
        // 値を取得
        // 配列として返す
        $entries = array();
        foreach ($items as $item) {
            $entries[] = get_object_vars($item);
        }
 
        return $entries;
    }
 
    protected function getItems()
    {
        $name = $this->feed->getName();
        switch ($name) {
            case 'RDF':     // RDF
                $items = $this->feed->item;
                break;
            case 'rss':     // RSS2.0
                $items = $this->feed->channel->item;
                break;
            case 'feed':    // Atom
                $items = $this->feed->entry;
                break;
            default:
                $items = array();
                break;
        }
 
        return $items;
    }
 
    protected function sendJson($data = array())
    {
        header('Content-Type: application/json; charset=UTF-8');    // JSON形式
        header('X-Content-Type-Options: nosniff');  // IEがContent-Typeヘッダーを無視してコンテンツの内容を解析するのを防ぐ
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);    // エスケープしてJSONで出力する
        exit;
    }
 
}
new AjaxFeed();

?>