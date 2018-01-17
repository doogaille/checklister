<?php
    /*
        This file is part of Checklister.

        Checklister is free software: you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation, either version 3 of the License, or
        (at your option) any later version.

        Checklister is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with Checklister.  If not, see <http://www.gnu.org/licenses/>.
    */
    require_once("config.php");

//    mysql_connect($config['sql_srv'],$config['sql_user'],$config['sql_pwd']);
//    mysql_select_db($config['sql_db']);
//
//    mysql_query("SET NAMES 'utf8';");

    /* Connexion à la base de donnée */
    $pdo = new PDO('mysql:host='.$config['db_host'].';dbname='.$config['db_bdd'], $config['db_user'],$config['db_pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8");

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Checklister</title>
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <link rel="stylesheet" href="css/base.css" media="screen" />
        <link rel="stylesheet" href="css/print.css" media="print" />
    </head>
    <body>
        <div id="header">
            <div class="centreur">
                <img src="images/logo_awedia.png" alt="Logo Awedia" />
                <h1>Gestionnaire de Checklist</h1>
            </div>
        </div>
        <form id="checklist_creator" method="post" action="submit.php?act=createChecklist">
            <h2>Créer une nouvelle checklist</h2>
            <p>
                <input type="text" id="checklist_nom" name="checklist_nom" placeholder="Nom" required="required" /> <button type="submit">Créer</button>
            </p>
        </form>
        <form id="checklist_selector" method="get">
            <h2>Gérer une checklist</h2>
            <p>
                <label for="checklist_id">Choisir : </label>
                <select id="checklist_id" name="checklist_id" required="required">
                    <option value="">-- Séléctionner --</option>
                    <?php
                        $req = "SELECT * FROM checklists";
                        $res = $pdo->query($req);
                        $res->execute();

                        while($row = $res->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="'.$row['id'].'" ';
                            if(!empty($_GET['checklist_id']) && $_GET['checklist_id'] == $row['id']) echo 'selected="selected"';
                            echo '>'.$row['nom'].'</option>'."\n";
                        }
                    ?>
                </select>
            </p>
        </form>
        <script type="text/javascript">
                $(document).ready(function() {
                    $('#checklist_id').on('change',function() {
                        if($(this).val() != '') {
                            $('#checklist_selector').submit();
                        }
                    });
                });
        </script>
        <?php
            if(!empty($_GET['checklist_id'])) {

                $req = "SELECT id, nom FROM checklists WHERE id=".intval($_GET['checklist_id'])." LIMIT 1";
                $res = $pdo->query($req);

                while($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $datas = $row;
                }
        ?>
        <h1 style="width:960px; margin:0 auto; text-align:center; margin-top:40px; font-size:26px; line-height:1.5; margin-bottom:25px;">Checklist : <?php echo $datas['nom']; ?><br /><a class="btn" href="submit.php?act=deleteChecklist&checklist_id=<?php echo intval($_GET['checklist_id']); ?>">Supprimer la checklist</a></h1>
        <form id="checklist_item_creator" method="post"  action="submit.php?act=createItemChecklist">
            <a class="imprimer" href="javascript:window.print();">Imprimer cette checklist</a>
            <?php 
                if(!empty($_GET['checklist_id'])) {
                    echo '<input type="hidden" name="checklist_id" value="'.intval($_GET['checklist_id']).'" />'."\n";
                }
            ?>
            <p>
                <label for="checklist_item">Ajouter un élément : </label>
                <input id="checklist_texte" name="checklist_texte" placeholder="Nouvel élément" required="required" style="width:300px" />
                <button type="submit">Ajouter</button>
                
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#checklist_texte').focus();
                    });
                </script>
            </p>
        </form>
        <div id="liste_items">
        <?php
                $req = "SELECT id, texte FROM checklists_items WHERE id_checklist=".intval($_GET['checklist_id']);
                $res = $pdo->query($req);
                
                $i = 0;
                while($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    
                    $id = $row['id'];
                    $texte = $row['texte'];
                    if($i%2 == 0) {
                        $class = 'paire';
                    } else {
                        $class = 'impaire';
                    }
                    echo '<p class="'.$class.'">'.$texte.' <a class="btn" href="submit.php?act=deleteItemChecklist&checklist_id='.intval($_GET['checklist_id']).'&item_id='.$id.'">Supprimer</a></p>';
                    $i++;
                }
                
            }
        ?>
        </div>
    </body>
</html>
