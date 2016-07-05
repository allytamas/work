<!DOCTYPE html>
<html lang="ro">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Test interfață Web pentru dispozitive mobile</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/grayscale.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <i class="fa fa-play-circle"></i>  <span class="light">Test interfață Web pentru dispozitive </span> mobile
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="index.html">Acasă</a> <!--#about..About-->
                    </li>
                   <!-- <li>
                        <a class="page-scroll" href="#downloads">ABOUT</a> Downloads
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li> -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Intro Header -->
   <!--  <header class="intro"> </header>
          <div class="intro-body"> </div> 
         <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        
                        <h1 class="brand-heading"> MOBILE FRIENDLY? </h1>
                        <br></br>
                    </div>
                </div>
            </div>
        </div>                
    </header>-->

     <section id="about" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>RAPORT EVALUARE TEST</h2>


<!--PROGRESS BAR-->
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" style="width: 20%">
                            10% <span class="sr-only">10% Complete (danger)</span>
                            </div>
                            <div class="progress-bar progress-bar-warning progress-bar-striped" style="width: 45%">
                            50%<span class="sr-only">50% Complete (warning)</span>
                            </div>
  
                            <div class="progress-bar progress-bar-success progress-bar-striped" style="width: 35%">
                            40% <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div><br>


<?php
require 'db.php';
include 'simple_html_dom.php';




$url = $_POST["url"];

// check if url address is well-formed
    if (!filter_var($url, FILTER_VALIDATE_URL) === false) {

        echo("$url is a valid URL");
        $content = file_get_contents($url);
        $contentArray = split("\n",$content);

        $query = "SELECT * FROM reguli";

        if ($result = $mysqli->query($query)) {

            $nrReguli=$result->num_rows;

            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {

                // id_regula / nume / context / tipcontext / entitate / tipentitate / conditie / valoare / feedback

                $id_regula=$row['id_regula'];
                $nume=$row['nume'];
                $context=$row['context'];
                $tipcontext=$row['tipcontext'];
                $entitate=$row['entitate'];
                $tipentitate=$row['tipentitate'];
                $conditie=$row['conditie'];
                $valoare=$row['valoare'];
                $feedback=$row['feedback'];
                $potriviri = 0;
                $potriviriRanduri = array();
//1
                if (is_null($context)) {

                    $cautare = "/(<".$entitate."\b)/i";

                    foreach ($contentArray as $key => $value) {
                        preg_match_all($cautare, $value, $matches, PREG_SET_ORDER);
                        $potriviri = $potriviri+count($matches);
                        if (count($matches)>0 ) $potriviriRanduri[]=$key;
                    }

                    switch($conditie) {
                            case '=': 
                                $pass = ($potriviri == $valoare) ? true : false ;
                                break;
                            case '<': 
                                $pass = ($potriviri < $valoare) ? true : false ;
                                break;
                            case '>': 
                                $pass = ($potriviri > $valoare) ? true : false ;
                                break;
                    }

                    if ($pass) {
                        $scor = 1;
                        echo "<br> ".$id_regula." - scor - ".($scor*100)."%";
                    } else {
                        $scor = 0;
                        echo "<br>".$id_regula." - scor - ".($scor*100)."% ";
                        echo $feedback;
                        echo "<br>verifica liniile: ";
                        foreach ($potriviriRanduri as $key => $value) {
                            echo " * ".$value." * ";
                        }  
                    }
                    

//INSERT 
//$query = "INSERT INTO subscribers (email, referral_id, user_id, ip_address)
 //VALUES ('$user_email', '$user_refer', '$user_share', '{$_SERVER['REMOTE_ADDR']}')"
                
                } elseif (is_null($entitate)) {
                    echo "<br>".$id_regula."CONTEXT not null, entitate null <br>";

                    if ($tipcontext == 0) {
                         $cautare ="/(<".$context.".*>(.*?)<\/".$context.">)/i";
                    } else {  
                        $cautare ="/( ".$context."=[\"'](.*?)[\"'])/i";
                    }

                    $nrTotal=0;
                    $nrOK=0;
                   foreach ($contentArray as $key => $value) {
                        preg_match_all($cautare, $value, $matches, PREG_SET_ORDER);
                        //$potriviri = $potriviri+count($matches);
                        if (count($matches)>0 ) {
                            
                            $nrTotal=$nrTotal+1;
                            $nrCaractere = strlen($matches[0][2]);
                            echo "<br>******** linia ".($key+1)." --caract ". $nrCaractere." ******** <br>";
                            switch($conditie) {
                                case '=': 
                                    $pass = ($nrCaractere == $valoare) ? true : false ;
                                    break;
                                case '<': 
                                    $pass = ($nrCaractere < $valoare) ? true : false ;
                                    break;
                                case '>': 
                                    $pass = ($nrCaractere > $valoare) ? true : false ;
                                    break;
                            }
                            echo "<br>pass este ".$pass."<br>";
                            if ($pass) {
                                $nrOK = $nrOK+1;
                            } 
                            echo "<br>******** end test ******** <br>";
                        }
                        
                    }
                    echo "<br>nr ok ".$nrOK. " si total este" . $nrTotal."<br>";
                    $scor=$nrOK/$nrTotal;

                        echo "<br>".$id_regula." - scor - ".($scor*100)."% ";
                        //echo $feedback;
                        //echo "<br>verifica liniile: ";
                        

                    echo htmlspecialchars($cautare);


                } else { 

                    $cautare = "/(<".$context."\b)/i";

                    if ($tipcontext == 0) {
                         $cautare ="/(<".$context.".*>(.*?)<\/".$context.">)/i";
                    } else {  
                        $cautare ="/( ".$context."=[\"'](.*?)[\"'])/i";
                    }

                    foreach ($contentArray as $key => $value) {
                        preg_match_all($cautare, $value, $matches, PREG_SET_ORDER);
                        $potriviri = $potriviri+count($matches);
                        if (count($matches)>0 ) $potriviriRanduri[]=$key;
                    }

                    switch($conditie) {
                            case '=': 
                                $pass = ($potriviri == $valoare) ? true : false ;
                                break;
                            case '<': 
                                $pass = ($potriviri < $valoare) ? true : false ;
                                break;
                            case '>': 
                                $pass = ($potriviri > $valoare) ? true : false ;
                                break;
                    }

                    if ($pass) {
                        $scor = 1;
                        echo "<br> ".$id_regula." - scor - ".($scor*100)."%";
                    } else {
                        $scor = 0;
                        echo "<br>".$id_regula." - scor - ".($scor*100)."% ";
                        echo $feedback;
                        echo "<br>verifica liniile: ";
                        foreach ($potriviriRanduri as $key => $value) {
                            echo " * ".$value." * ";
                        }  
                    }) {
                    # code...
                }





/*                    $haystack ="<html><title class=\"\">Titlu meu este lung</title></head>";
                    echo "<br>**** test title **** <br>";
                    echo $haystack;

                    preg_match_all($cautare, $haystack, $matches, PREG_SET_ORDER);

                    $nrCaractere = strlen($matches[0][2]);
                   

                    echo $nrCaractere;

                    echo "<br>******** end test ******** <br>";*/
//---------------------------------
/*                if (!is_null($entitate)) {
                    echo "suntem in cazul 1"; // cazul 1 inseamna ca vom cauta entitate in context si vom avea pass daca se satisface conditia cu valoarea asociata
                    $cautam = 

                    foreach ($variable as $key => $value) {
                        # code...
                    }
                } else {
                    echo "cazul 2"; //cazul 2 inseamna ca vom avea pass daca dimensiunea continutului din context satisface conditia si valoarea asociata
                }*/

            }
                        /* free result set */
            $result->free();
        }

/* close connection */
$mysqli->close();

        
        //preg_match_all($pattern7, $testcontent, $matches, PREG_SET_ORDER);
        

        
        /*foreach ($contentArray as $key => $value) {
            //echo $key.". ". $value."<br>";
            //echo $key.". ". htmlspecialchars($value)."<br>";
            preg_match_all($pattern, $value, $matches, PREG_SET_ORDER);
                $nrIMG = $nrIMG + count($matches);
                if (count($matches) > 0) $allMatches[] = $key;
            
            preg_match_all($pattern2, $value, $matches, PREG_SET_ORDER);
                $nrCorect = $nrCorect + count($matches);
                if (count($matches) > 0) $goodMatches[] = $key;

         
        }*/
 
        //printf("%.02f%%\n", $nrCorect/$nrIMG * 100);

} else {
    echo("$url is not a valid URL");
}
?>
     </div>
        </div>
    </section>
      <!-- Footer -->
    <footer>
        <div class="container text-center">
             <!--<p>Copyright &copy; Your Website 2014</p>-->
            <p>Lucrare Licență 2016</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>


    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.js"></script>

</body>

</html>