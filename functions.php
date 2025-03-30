<?php
    function checkLogin(){
        if(isset($_SESSION['login'])){
            return true;
        }
        return false;
    }
    function checkPowerDifferentThan($x){
        if($_SESSION['power'] != $x){
            return true;
        }
        return false;
    }
?>