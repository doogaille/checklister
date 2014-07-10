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

    mysql_connect($config['sql_srv'],$config['sql_user'],$config['sql_pwd']);
    mysql_select_db($config['sql_db']);

    mysql_query("SET NAMES 'utf8';");

    /* Vérification de la présence d'une action en paramètre GET */
    if(!empty($_GET['act'])) {
        if($_GET['act'] == 'createChecklist') {
            if(empty($_POST['checklist_nom'])) {
                echo 'Nom invalide';
            } else {
                $req = "
                    INSERT INTO checklists (
                        `nom`
                    ) VALUES (
                        '".mysql_real_escape_string($_POST['checklist_nom'])."'
                    );
                ";
                
                $res = mysql_query($req);
                
                if($res) {
                    $id_insert = mysql_insert_id();
                    header("Location: index.php?checklist_id=".$id_insert);
                } else {
                    echo 'Impossible de créer la checklist';
                }
            }
        } else if($_GET['act'] == 'createItemChecklist') {
            if(empty($_POST['checklist_id'])) {
                echo 'Identifiant de checklist erroné';
            } else if(empty($_POST['checklist_texte'])) {
                echo 'Texte invalid';
            } else {
                $req = "
                    INSERT INTO checklists_items (
                        `id_checklist`,
                        `texte`
                    ) VALUES (
                        ".intval($_POST['checklist_id']).",
                        '".mysql_real_escape_string($_POST['checklist_texte'])."'
                    );
                ";
                
                $res = mysql_query($req);
                
                if($res) {
                    header("Location: index.php?checklist_id=".intval($_POST['checklist_id']));
                } else {
                    echo 'Impossible d\'ajouter l\'élément à la checklist';
                }
            }
        } else if($_GET['act'] == 'deleteItemChecklist') {
            if(empty($_GET['checklist_id'])) {
                echo 'Identifiant de checklist erroné';
            } else if(empty($_GET['item_id'])) {
                echo 'Identifiant d\'item de checklist erroné';
            } else {
                $req = "DELETE FROM checklists_items WHERE id=".intval($_GET['item_id'])." LIMIT 1";
                $res = mysql_query($req);
                
                if($res) {
                    header("Location: index.php?checklist_id=".intval($_GET['checklist_id']));
                } else {
                    echo 'Impossible de supprimer l\'élément de la checklist';
                }
            }
        } else if($_GET['act'] == 'deleteChecklist') {
            if(empty($_GET['checklist_id'])) {
                echo 'Identifiant de checklist erroné';
            } else {
                $req = "DELETE FROM checklists WHERE id=".intval($_GET['checklist_id'])." LIMIT 1";
                $res = mysql_query($req);
                
                if($res) {
                    header("Location: index.php");
                } else {
                    echo 'Impossible de supprimer la checklist';
                }
            }
        } else {
            echo 'action invalide';   
        }
    }
    
?>