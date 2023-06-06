<?php
session_start();
include('../includes/requetes.php');

$langue = 'fr'; // langue par défaut
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    // récupérer la langue du navigateur
    $langue = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

// on stocke le cookie pdt 10 secondes juste pour l'exemple
setcookie('langue', $langue, time() + (10), '/');

// mettre le texte dans la bonne langue
if (isset($_COOKIE['langue']) && $_COOKIE['langue'] == 'en') {
    // texte en anglais
    $question = '...';
    $reponse = 'Hello! Ask me a question!';
} else {
    // texte en français par défaut
    $question = '...';
    $reponse = 'Bonjour ! Posez-moi une question !';
}


if (isset($_POST['question'])) {
    $bdd = connexion('site');
    $question = htmlspecialchars($_POST['question']);
    $question = strtolower($question);
    $question = trim($question);
    $question = str_replace('.', ' ', $question);
    $question = str_replace(',', ' ', $question);
    $question = str_replace(';', ' ', $question);
    $question = str_replace(':', ' ', $question);
    $question = str_replace('\'', ' ', $question);
    $question = str_replace('"', ' ', $question);
    $question = str_replace('(', ' ', $question);
    $question = str_replace(')', ' ', $question);
    $question = str_replace('[', ' ', $question);
    $question = str_replace(']', ' ', $question);
    $question = str_replace('{', ' ', $question);
    $question = str_replace('}', '  ', $question);

    $mots = explode(' ', $question);
    
    $reponse = '';

    foreach ($mots as $mot) {
        $mot = htmlspecialchars($mot);
        $req = $bdd->prepare('SELECT * FROM chatbot WHERE mot = ?');
        $req->execute(array($mot));
        $donnees = $req->fetch();

        if ($donnees) {
            $reponse = $donnees['reponse'];
            //on rajoute le lien vers les cours si la réponse est "cours"
            if ($donnees['mot'] == 'cours') {
                $reponse = 'Vous pouvez trouver les cours sur la page suivante  :  ';
                $reponse .= '<a href="../Cours/index.php">Redirection vers la page des cours</a>';
            }
            //on rajoute le lien vers le forum si la réponse est "forum"
            if ($donnees['mot'] == 'forum') {
                $reponse = 'Vous pouvez trouver le forum sur la page suivante  :  ';
                $reponse .= '<a href="../Forum/index.php">Redirection vers la page du forum</a>';
            }
        }
    }

    if ($reponse == '') {
        $reponse = 'Je ne comprends pas votre question';
    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>School learning</title>
    <link rel="stylesheet" href="style.css">
    <noscript>
        <style>
            .header {
                backdrop-filter: blur(20px);
            }
        </style>
</noscript>
</head>

<body>
    <header class="header"> 
        <h2 class="logo">School Learning</h2>
        <nav class="navigation">
            <a href="#" class="nav">Accueil</a>
            <a href="../Services/index.php" class="nav">Services</a>
            <a href="../Contact/index.php" class="nav">Contact</a>
            <a href="../Vente/index.php" class="nav">Boutique</a>
            
<?php
if(!isset($_SESSION['pseudo'])){
    echo '<a href="../Connect/index.php" class="popup">Connexion</a>';
}
else{
    if(countUnreadMessages($_SESSION['id']) > 0)
        echo '<a href="../Messagerie/index.php" class="nav"><span class="notif breath">' . countUnreadMessages($_SESSION['id']) . '</span>Messagerie</a>';
    else{
        echo '<a href="../Messagerie/index.php" class="nav">Messagerie</a>';
    }

    echo '<a href="../Cours/index.php" class="nav">Cours en ligne</a>';
    echo '<a href="../quiz/index.php" class="nav">Se tester</a>';
    echo '<a href="../Forum/index.php" class="nav">Forum</a>';
    echo '<a href="../perso/index.php" class="nav">Mon compte</a>';    

    if( $_SESSION['permission'] == 1){
        echo '<a href="../administration/index.php" class="nav">Administration</a>';
    }

    echo '<a href="../php/deconnexion.php" class="popup">Déconnexion</a>';
}
?>
        </nav>
    </header>

    <main>


        <div id = "chatbot" class = "chatbot chatbot-display">
            <h3>Chatbot</h3>
            <span class="icon-close"><ion-icon name="close"></ion-icon></span>
            <?php
            echo 'Vous : ' . $question . '<br>';
            echo 'Chatbot : ' . $reponse . '<br>';
            ?>
            <form action="index.php" method="post" id="chatbot-form">
                <input type="text" name="question" placeholder="Posez votre question">
                <input type="submit" value="Envoyer">
            </form>
        </div>
        <button type="button" class="chatbot-btn chatbot-hide" onclick="chatbotDisplay()"><ion-icon name="chatbox-ellipses-outline"></ion-icon></button>


        <h1>Bienvenue !</h1>
        <div class="text">
            <?php 
            if (isset($_COOKIE['langue']) && $_COOKIE['langue'] != 'fr') {
                echo "<p>School Learning is an exceptional website that provides high-quality educational tools for students of all levels and disciplines. This site is the perfect place for students who are looking to review their courses, engage with other students, and succeed in their studies.

                Firstly, School Learning offers a variety of educational resources to help students improve their skills and succeed in their studies. The site provides online courses, practical exercises, tests and quizzes, as well as video tutorials to help students understand the most difficult concepts. These resources are designed to be interactive and adaptive, offering a personalized learning experience for each student.
                
                Additionally, School Learning offers an online discussion platform where students can communicate with other students who are studying the same discipline. This feature is very useful for students who are seeking answers to specific questions, sharing knowledge, or simply discussing their studies. Students can also form study groups and work together on projects or assignments.
                
                Another advantage of School Learning is the presence of online tutors. 

                </p>";
            } 
            
            else {
                echo 
                "<p>School Learning est un site web exceptionnel qui offre des outils éducatifs de haute qualité aux étudiants de tous les niveaux et de toutes les disciplines. Ce site est l'endroit idéal pour les élèves qui cherchent à réviser leurs cours, à discuter avec dautres élèves et à réussir leurs études.

                Tout d'abord, School Learning offre une variété de ressources pédagogiques pour aider les élèves à améliorer leurs compétences et à réussir leurs études. Le site propose des cours en ligne, des exercices pratiques, des tests et des quiz, ainsi que la possibilité de contacter des professeurs pour aider les étudiants à comprendre les concepts les plus difficiles. Ces ressources sont conçues pour être interactives et adaptatives, offrant une expérience d'apprentissage personnalisée pour chaque étudiant.
                
                En outre, School Learning offre une plateforme de discussion en ligne où les élèves peuvent communiquer avec d'autres élèves et des enseignants. Cette fonctionnalité est très utile pour les étudiants qui cherchent à obtenir des réponses à des questions spécifiques, partager des connaissances ou simplement discuter de leurs études. Les étudiants peuvent également travailler ensemble sur des projets ou des devoirs grâce à la messagerie.
                
                Un autre avantage de School Learning est la présence de tuteurs en ligne.

                </p>";
            }
            ?>
        </div>

        <div class="text suite">
            <?php
            if(isset($_COOKIE['langue']) && $_COOKIE['langue'] != 'fr'){
                echo "<p>Students who need additional help can access online tutors in real time to get their questions answered or to receive personalized support. Tutors are highly trained and have proven experience in their field, ensuring students receive a high level of support.
                
                Finally, School Learning is a user-friendly website that is easy to navigate and use. Students can access all the resources and features with ease, without the need for special technical expertise. In addition, the site is available on a variety of platforms, including computers, tablets, and smartphones, making it convenient and accessible for all students.
                
                All in all, School Learning is an exceptional website for students looking to improve their skills, communicate with other students, and achieve academic success. With its high-quality educational resources, online discussion features, and online tutors, this site offers an unparalleled learning experience for all students. If you are looking to succeed in your studies, School Learning is the website for you.
                
                </p>";

            }
            else{
                echo "<p>Les étudiants qui ont besoin d'aide supplémentaire peuvent accéder à des tuteurs en ligne en temps réel pour obtenir des réponses à leurs questions ou pour bénéficier d'un soutien personnalisé. Les tuteurs sont hautement qualifiés et disposent d'une expérience avérée dans leur domaine, ce qui garantit aux étudiants un niveau de soutien élevé.
                
                Enfin, School Learning est un site web convivial qui est facile à naviguer et à utiliser. Les étudiants peuvent accéder à toutes les ressources et fonctionnalités avec facilité, sans avoir besoin dune expertise technique particulière. En outre, le site est disponible sur une variété de plateformes, y compris les ordinateurs, les tablettes et les smartphones, ce qui rend son utilisation pratique et accessible pour tous les étudiants.
                
                En somme, School Learning est un site web exceptionnel pour les étudiants qui cherchent à améliorer leurs compétences, à communiquer avec d'autres élèves et à réussir leurs études. Avec ses ressources pédagogiques de haute qualité, ses fonctionnalités de discussion en ligne et ses tuteurs en ligne, ce site offre une expérience d'apprentissage inégalée pour tous les étudiants. Si vous cherchez à réussir vos études, School Learning est le site web qu'il vous faut.
                
                </p>";
            }
            ?>
        </div>

        <div class="text suite">
            <?php
            if(isset($_COOKIE['langue']) && $_COOKIE['langue'] != 'fr'){
                echo "<p>In addition, School Learning is a website that adapts to the needs of each student. Learning resources and communication tools are designed to accommodate different learning styles and individual preferences. Students can learn at their own pace, depending on their skill level and schedule. 
                
                In addition, progress tracking features allow students to track their progress and know exactly where they are in their learning journey. With all of these benefits, School Learning is truly the ultimate website for students looking to improve their education and succeed in their professional lives.

                </p>";
            }
            else {
            
                echo "<p>En outre, School Learning est un site web qui s'adapte aux besoins de chaque étudiant. Les ressources pédagogiques et les outils de communication sont conçus pour répondre aux différents styles d'apprentissage et aux préférences individuelles. Les étudiants peuvent apprendre à leur propre rythme, en fonction de leur niveau de compétence et de leur emploi du temps. 
                
                De plus, les fonctionnalités de suivi de la progression permettent aux étudiants de suivre leur évolution et de savoir exactement où ils en sont dans leur parcours d'apprentissage. Avec tous ces avantages, School Learning est vraiment le site web ultime pour les étudiants qui cherchent à améliorer leur niveau d'études et à réussir dans leur vie professionnelle.
                    
                </p>";
            }
            ?>
        </div>

    </main>

    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>
</html>
