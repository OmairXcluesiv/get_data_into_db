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
    price VARCHAR(50),
    PRIMARY KEY(sno))');
} catch (Exception $e) {
}

function data_refine($data){
  $st = strip_tags($data);  // Removes HTML tags here
  $ws = preg_replace('/\s+/', '', $st); // Removes whitespaces from here
  return $ws;
}

$count= 0; // Intial state of counter
$max_loop= 50; // set the loop value (end)
$loop_end = 300;

   for($i=0;$i<=$max_loop;$i++){
     
     // link of config ebay to get the data
     if($count>=51){
     $html = scraperwiki::scrape("http://www.ebay.com/sch/i.html?_from=R40&_sacat=0&_nkw=American+Revolutionary+War&_pgn=".$ic."&_skc=50&rt=nc");
     }
     
     $dom = new simple_html_dom();
     $dom->load($html);
     $r = $dom->find("a.vip");
     $m = $dom->find("li.lvprice");

    //To count the collected data
   if($r[$i]  || $m[$i] !=""){ 
    $count++;
   if($count>$max_loop)
     $icount = 1;
     $ic += $icount;
   }
     
 $sno = $i;
 echo "Sno: " . $sno . "\n";
 echo "Product Title: " .  strip_tags($r[$i]) . "\n";
 echo "Product Price: " .  data_refine($m[$i]) . "\n\n\n\n\n";
 $pt = strip_tags($r[$i]);
 $pp = data_refine($m[$i]);
     

 $articles = array(array('sno' => $sno , 'title' => strip_tags($r[$i]) , 'price' => data_refine($m[$i])));
// print_r($articles);
 //die();
 foreach ($articles as $article) {
  $exists = $db->query("SELECT * FROM data WHERE sno = ". $db->quote($article->sno))->fetchObject();
  if (!$exists) {
    $sql = "INSERT INTO data(sno, title, price) VALUES(:sno, :title, :price)";
  } else {
    $sql = "UPDATE data SET title = :title, price = :price WHERE sno = :sno";
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

