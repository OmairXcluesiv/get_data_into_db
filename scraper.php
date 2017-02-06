<?

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';

// configuration on db driver of sqlite
$db = new PDO('sqlite:data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// creating sqlite database 
try {
  $db->query('CREATE TABLE data(
    sno int(10),
    title VARCHAR(100),
    price VARCHAR(10))');
} catch (Exception $e) {
}

function data_refine($data){
  $st = strip_tags($data);  // Removes HTML tags here
  $ws = preg_replace('/\s+/', '', $st); // Removes whitespaces from here
  return $ws;
}

// link of config ebay to get the data
$html = scraperwiki::scrape("http://www.ebay.com/sch/i.html?_from=R40&_trksid=p2050601.m570.l1313.TR0.TRC0.H0.XAmerican+Revolutionary+War&_nkw=American+Revolutionary+War&_sacat=0");

$count= 0; // Intial state of counter
$max_loop= 50; // set the loop value (end)

   for($i=0;$i<=$max_loop;$i++){
     $dom = new simple_html_dom();
     $dom->load($html);
     $r = $dom->find("a.vip");
     $m = $dom->find("li.lvprice");

    //To count the collected data
   if($r[$i]  || $m[$i] !=""){ $count++;}
 
 $sno = $i;
 echo "Sno: " . $sno . "\n";
 echo "Product Title: " .  data_refine($r[$i]) . "\n";
 echo "Product Price:"  .  data_refine($m[$i]) . "\n\n\n\n\n";
 
    
 $articles = array(array('sno' => $sno , 'title' => strip_tags($r[$i]) , 'price' => $no_ws));
 
  foreach ($articles as $article) {
  $exists = $db->query("SELECT * FROM data");
  $sql = "INSERT INTO data(sno, title, price) VALUES(:sno, :title, :price)";
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

