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
    price VARCHAR(10),
    PRIMARY KEY (sno))');
} catch (Exception $e) {
}

// link of config ebay to get the data
$html = scraperwiki::scrape("http://www.ebay.com/sch/i.html?_from=R40&_trksid=p2050601.m570.l1313.TR0.TRC0.H0.XAmerican+Revolutionary+War&_nkw=American+Revolutionary+War&_sacat=0");

$count= 0; // Intial state of counter
$max_loop = 50; // set the loop value 

   for($i=0;$i<=$max_loop;$i++){
     $dom = new simple_html_dom();
     $dom->load($html);
     $r = $dom->find("a.vip");
     $m = $dom->find("li.lvprice");

    //To count the collected data
   if($r[$i]  || $m[$i] !=""){ $count++;}
 
 $sno = $i;
 echo "Sno: " . $sno . "\n";
 echo "Product Title: " . strip_tags($r[$i]) . "\n";
 $p_text = strip_tags($m[$i]); // replace all HTML tags with plain text 
 $no_ws =  preg_replace('/\s+/', '', $p_text); // remove one or more than one whitespace from grabbed data
 echo "Product Price:"  . $no_ws . "\n\n\n\n\n";
 
 $articles = array(array('sno' => $sno , 'title' => strip_tags($r[$i]) , 'price' => $no_ws));
 
 

}
?>
