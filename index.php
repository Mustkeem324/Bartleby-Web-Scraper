<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['url'])) {
        $text = filter_var($_GET['url'], FILTER_VALIDATE_URL);
        if ($text === false) {
            echo "Invalid URL provided";
            exit;
        }
    } else {
        echo "URL parameter missing";
        exit;
    }

    $url.= $text;
function accessToken(){
    //refreshToken paste in txt file
    $refreshTokentxt=file_get_contents('refreshToken.txt');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nk6xemh85d.execute-api.us-east-1.amazonaws.com/prod/user/refresh-token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"refreshToken":"'.$refreshTokentxt.'"}',
        CURLOPT_HTTPHEADER => array(
            'authority: nk6xemh85d.execute-api.us-east-1.amazonaws.com',
            'accept: */*',
            'accept-language: en-US,en;q=0.9',
            'content-type: application/json',
            'dnt: 1',
            'origin: https://www.bartleby.com',
            'referer: https://www.bartleby.com/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: cross-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $info = curl_getinfo($curl);
    //echo $response;
    if($info["http_code"] =200){
        $data = json_decode($response, true);
        $accessToken=$data['data']['accessToken'];
        $refreshToken=$data['data']['refreshToken'];
        $idToken=$data['data']['bartlebyTokens']['idToken'];
        $refreshToken2=$data['data']['bartlebyTokens']['refreshToken'];
        return [$accessToken,$refreshToken,$idToken,$refreshToken2];
    }
    else{
        return[null,null,null,null];
    }
}
function getlike($lastPart){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nk6xemh85d.execute-api.us-east-1.amazonaws.com/prod/learning/qna/'.$lastPart.'/stats',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'authority: nk6xemh85d.execute-api.us-east-1.amazonaws.com',
            'accept: */*',
            'accept-language: en-US,en;q=0.9',
            'dnt: 1',
            'origin: https://www.bartleby.com',
            'referer: https://www.bartleby.com/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: cross-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
        ),
    ));
    $responseid = curl_exec($curl);
    curl_close($curl);
    $info = curl_getinfo($curl);
    //echo $response;
    if($info["http_code"] =200){
        $like =json_decode($responseid, true)["data"]["numberOfUpVotes"];
        $dislike =json_decode($responseid, true)["data"]["numberOfDownVotes"];
        return [$like,$dislike];
    }
    else{
        return[null,null];
    }
}
$getToken = accessToken();
$parts = explode('/', $url);
$lastPart = end($parts);
$feedback =getlike($lastPart);
    if(preg_match('/questions-and-answers/', $url)){
        if(isset($getToken[0]) && isset($getToken[1]) && isset($getToken[2])){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://nk6xemh85d.execute-api.us-east-1.amazonaws.com/prod/qna/answer/'.$lastPart,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authority: nk6xemh85d.execute-api.us-east-1.amazonaws.com',
                'accept: */*',
                'accept-language: en-US,en;q=0.9',
                'authorization: Bearer '.$getToken[0],
                'dnt: 1',
                'origin: https://www.bartleby.com',
                'referer: https://www.bartleby.com/',
                'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Linux"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: cross-site',
                'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            $dataAns = json_decode($response, true);
            $question=$dataAns['data']['question']['text'];
            $questionImg=$dataAns['data']['question']['images'];
            $ansstatus=$dataAns['data']['answer']['status'];
            $stepsans=$dataAns['data']['answer']['steps'];
            $stepsans2=count($dataAns['data']['answer']['steps']);
            foreach($questionImg as $questionImg2){
                $questionImghtml='<img src='.$questionImg2['imageUrl'].' width="1095" height="417"><br>';
            }
            if($ansstatus==='Accepted'){
                $anstext = '';
                $stepcount = '';
                foreach ($stepsans as $index => $stepsanshtml) {
                    $stepName = '<span style="color: red;">Step ' . ($index + 1) . '/' . $stepsans2 . '</span>';
                    $elements= '<h2>' . $stepName . '</h2>';
                    $anstext .= $elements.$stepsanshtml['text'];
                    $stepcount .= $stepsanshtml['header'];
                }
                $folderPath = 'Bartlebyhtml';
                $filename = md5($url); // Use the URL as a unique identifier for the response file
                $filePath = __DIR__ . '/' . $folderPath . '/' . $filename;
                if (!is_dir($folderPath)) {
                    mkdir($folderPath, 0755, true); // 0755 is a common permission setting
                }
                if (file_exists($filePath)) {
                    $answerhtml = file_get_contents($filePath);
                }else{
                    $answerhtml='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <meta name="viewport" content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script></head><body><div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">'.$url.'</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx">'.$question.'<br>'.$questionImghtml.'</div> <div class="likedislike"> <h3>Like: '.$feedback[0].'<br>Dislike:  '.$feedback[1].'</h3> </div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx">'.$anstext.'</div> </div> </div> </div> </div> </div></div><script type="text/x-mathjax-config">MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX","input/MathML","output/HTML-CSS","output/NativeMML"], extensions: ["tex2jax.js","mml2jax.js","MathMenu.js","MathZoom.js"], TeX: { extensions: ["AMSmath.js","AMSsymbols.js","noErrors.js","noUndefined.js"] } });</script><script type="text/javascript" src="https://cdn.mathjax.org/mathjax/2.0-latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script></body></html>';
                    file_put_contents($filePath, $answerhtml);
                }
                echo $answerhtml;
            }
        }
    }

    elseif(preg_match('/solution-answer/', $url)) {
        $pattern = '/\/(\d{13})\//';
        if (preg_match($pattern, $url, $matches)) {
            $isbn = $matches[1];
            //echo "ISBN-13: " . $isbn;
        } else {
            echo "ISBN not found in the URL.";
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nk6xemh85d.execute-api.us-east-1.amazonaws.com/prod/solution/'.$lastPart.'/'.$isbn.'?ignoreLastViewed=false',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'authority: nk6xemh85d.execute-api.us-east-1.amazonaws.com',
            'accept: */*',
            'accept-language: en-US,en;q=0.9',
            'authorization: Bearer '.$getToken[0],
            'dnt: 1',
            'origin: https://www.bartleby.com',
            'referer: https://www.bartleby.com/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: cross-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        $dataAns = json_decode($response, true);
        $question=$dataAns['data']['questionHtml'];
        $solutionSections=json_decode($response, true)["data"]["solutionJson"]["solutionSections"];
        foreach($solutionSections as $solutionSubsections){
            
            $sectionName=$solutionSubsections["solutionSubsections"];
            $anshtml='';
            foreach($sectionName as $sectionTexthtml){
                $anshtml.=$elements.$sectionTexthtml['sectionText'];
                //echo $anshtml;
            }
        }
        $folderPath = 'Bartlebyhtml';
        $filename = md5($url); // Use the URL as a unique identifier for the response file
        $filePath = __DIR__ . '/' . $folderPath . '/' . $filename;
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true); // 0755 is a common permission setting
        }
        if (file_exists($filePath)) {
            $answerhtml = file_get_contents($filePath);
        }else{
            $answerhtml='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <meta name="viewport" content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script></head><body><div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">'.$url.'</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx">'.$question.'</div> <div class="likedislike"></div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx">'.$anshtml.'</div> </div> </div> </div> </div> </div></div><script type="text/x-mathjax-config">MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX","input/MathML","output/HTML-CSS","output/NativeMML"], extensions: ["tex2jax.js","mml2jax.js","MathMenu.js","MathZoom.js"], TeX: { extensions: ["AMSmath.js","AMSsymbols.js","noErrors.js","noUndefined.js"] } });</script><script type="text/javascript" src="https://cdn.mathjax.org/mathjax/2.0-latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script></body></html>';
            file_put_contents($filePath, $answerhtml);
        }
        echo $answerhtml;
    }
}
