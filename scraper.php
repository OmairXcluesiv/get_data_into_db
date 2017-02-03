<?

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';

$db = new PDO('sqli
te:data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  $db->query('CREATE TABLE data(
    sno int(100),
    title VARCHAR(100),
    price int(10),
    PRIMARY KEY (sno))');
} catch (Exception $e) {
}

$html = scraperwiki::scrape("http://www.ebay.com/sch/i.html?_from=R40&_trksid=p2050601.m570.l1313.TR0.TRC0.H0.XAmerican+Revolutionary+War&_nkw=American+Revolutionary+War&_sacat=0");

$count= 0;
$max_loop = 50;
for($i=0;$i<=$max_loop;$i++){
 $dom = new simple_html_dom();
 $dom->load($html);
 $r = $dom->find("a.vip");
 $m = $dom->find("li.lvprice");

 if($r[$i]  || $m[$i] !=""){ $count++;}
 
 echo "Product Title: " . strip_tags($r[$i]) . "\n";
 $p_text = strip_tags($m[$i]);
 $no_ws =  preg_replace('/\s+/', '', $p_text);
 echo "Product Price:"  . $no_ws . "\n\n\n\n\n";
 
 articles = array(array('sno => "1", 'title' => strip_tags($r[$i]) , 'price' => $no_ws));
 
 foreach ($articles as $article) {
  $exists = $db->query("SELECT * FROM data WHERE sno = " . $db->quote($article->sno))->fetchObject();
  if (!$exists) {
    $sql = "INSERT INTO data(sno, title, price) VALUES(:sno, :title, :price)";
  } else {
    //$sql = "UPDATE data SET description = :description, article_timestamp = :article_timestamp WHERE guid = :guid";
  }
  $statement = $db->prepare($sql);
    $statement->execute(array(
    ':sno' => $article['sno'], 
    ':title' => $article['title'],
    ':price' => $article['price']
  ));
}

}
?>
