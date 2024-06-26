<?php
session_start();

require '../connect.php';
date_default_timezone_set('Europe/Paris');
if (isset($_SESSION['ctfcookies'])) {
    $valeurCookie = $_SESSION['ctfcookies'];
    $valeurCookieNOM = $_SESSION['ctfNOM'];
    $requeteverif = $connexion->prepare("SELECT key5 FROM timersociaux WHERE cookie = ?");
    $requeteverif->bind_param("s",$valeurCookie);
    $requeteverif->execute();
    $requeteverif->bind_result($valeur1);
    $requeteverif->fetch();
    if ($valeur1 != NULL) {
        header('Location: ../reseaux.php');
        exit();
    } else {
        $requeteverif->close();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Des fichiers cachés</title>
    <link rel="stylesheet" href="../css/select.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.js"></script>
    <link rel="stylesheet" href="../css/scrollbar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-beautify.js"></script>
    <style>
        body {
            overflow: hidden;
            background: url(../images/bg.png);
            background-color: #000309;
            background-position: right;
            background-size: cover;
            background-repeat: no-repeat;
            background-position-x: 350px;
        }

        .loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background-color: rgb(66, 66, 66);
            z-index: 13;
        }

        .progress {
            height: 100%;
            width: 0;
            background-color: #b80900;
            transition: width 1s ease;
        }

        .time {
            font-family: Arial, sans-serif;
            position: fixed;
            top: 10px;
            left: 10px;
            color: #b80900;
            font-size: 18px;
            font-weight: bold;
        }



        #case {
            width: 96px;
            height: 96px;
            border-radius: 100%;
            position: absolute;
            border-color: red; 
            border-style: dashed; 
            left: 80%;
            top: 25%;
            z-index: 2; 
        }
        #validButton {
            display: none;
            position: fixed;
            width: 23%;
            top: 55%;
            left: 2%;
            padding: 10px;
            animation: neon2 1s infinite alternate;
            cursor: pointer;
            color: #fff;
            border: 2px solid #bcbcbc;
            border-radius: 5px;
            font-size: 16px;
            transform-style: preserve-3d;
            perspective: 800px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0);
            background-color: transparent;
            margin-top: -10px;
            margin-bottom: 10px;
            background-color: #1a1a1a;
        }

        #validButton:hover {
            animation: neon2 1s infinite alternate;
            box-shadow: 0 0 20px rgba(23, 230, 116, 0.8);
            border: 2px solid #14c65e;
        }
        #runButton {
            position: fixed;
            width: 23%;
            top: 55%;
            left: 2%;
            padding: 10px;
            animation: neon 1s infinite alternate;
            cursor: pointer;
            color: #fff;
            border: 2px solid #e62117;
            border-radius: 5px;
            font-size: 16px;
            transform-style: preserve-3d;
            perspective: 800px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
            box-shadow: 0 0 10px rgba(230, 33, 23, 0);
            background-color: transparent;
            margin-top: -10px;
            margin-bottom: 10px;
            background-color: #1a1a1a;
        }
        #runButton:hover {
            animation: neon 1s infinite alternate;
            box-shadow: 0 0 20px rgba(230, 33, 23, 0.8);
            border: 2px solid #c61a14;
        }

        @keyframes neon {
            to {
                box-shadow: 0 0 40px rgba(230, 33, 23, 0.8);
            }
        }

        @keyframes neon2 {
            to {
                box-shadow: 0 0 40px rgba(184, 184, 184, 0.5);
            }
        }
        #tooltip {
            width: 17.2%;
            background-color: #272822;
            color: #fff;
            height: 190px;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
            position: fixed;
            left: 56%;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }
        .input {
            color: white;
            width: 20%;
            height: 26px;
            padding: 6px 15px;
            background: #1a1a1a;
            border: 2px solid #e62117;
            border-radius: 8px;
            top: 40%;
            position: fixed;
            left: 2%;

        }
        .input:hover {
            animation: neon 1s infinite alternate;
            box-shadow: 0 0 20px rgba(230, 33, 23, 0.8);
            border: 2px solid #c61a14;
        }
        .back {
            background: #ffffff08;
            width: 27%;
            height: 100%;
            top: 0;
            position: absolute;
            left: 0;
            border-radius: 27px;
            position: absolute;
        }
        .iframeInsta {
            position: absolute;
            left: 27%;
            width: 73%;
            height: 100%;
        }
        .question {
            color: white;
            text-align: center;
            width: 100%;
            display: block;
            font-family: Arial, sans-serif;
            top: 20%;
            position: absolute;
            font-size: 19px;
        }
        #result {
            color: white;
            text-align: center;
            width: 100%;
            display: block;
            font-family: Arial, sans-serif;
            top: 48.5%;
            position: absolute;
            font-size: 14px;
            z-index: -46;
        }
        .iframeInsta embed {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
<div class="loading-bar">
    <div class="progress" id="progress"></div>
    <div class="time" id="time">0:00</div>
</div>

        <div class="back">
        <span class="question">Où a été prise la photo <br>que Héloïse vous a envoyée ?</span>
        <button id="runButton" onclick="verifierValeur()">Valider</button>
        <input type="text" class="input" id="inputval" ></input>
        <span id="result"></span>
        <button id="validButton" onclick="redirect()">Valider</button>
</div>
<div class="iframeInsta">
        <embed src="instadownoaldimgetape4.html" >
</div>    

    <script src="../js/timerreseaux.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

<script>
    
function remplacerCaracteresSpeciaux(chaine) {
    let chaineModifiee = chaine.replace(/[éè]/gi, 'e').replace(/à/gi, 'a');
    let chaineModifieend = chaineModifiee.toLowerCase();
    return chaineModifieend;
}

var essai = 0
function encryptValue(value, secretKey) {
    var key = CryptoJS.enc.Utf8.parse(secretKey);
    var encryptedValue = CryptoJS.AES.encrypt(value, key, {
        mode: CryptoJS.mode.ECB, 
        padding: CryptoJS.pad.Pkcs7 
    });
    return encryptedValue.toString();
}

function verifierValeur() {
    var valeur = remplacerCaracteresSpeciaux(document.getElementById("inputval").value);
    console.log(valeur)
    let valeursAComparer = [
        "7t0VT0OFvfVNdxHGwO+eyKOwv1r9FSMBxLyxTwNinok=",
        "TaRAuSeWQ0wpwHJXObfYEZfXWIfuuEa/l8H/CgS0WOl2vNtRkTdm1E/Iqy4k678mrSLvQWKgLPHAEEw0FTvvXA=="
    ];

    if (valeursAComparer.includes(encryptValue(valeur, 1990))) {
        document.getElementById("result").innerHTML = "Réponse correcte";
        document.getElementById("validButton").style.display = "initial";
        document.getElementById("runButton").style.display = "none";
    }
    else {
        essai = essai + 1;
        document.getElementById("result").innerHTML = "Réponse incorrecte";
    }
}

function redirect(){
    window.location.href = "save5.php?nombre=" + essai;
}

</script>
</body>
</html>
