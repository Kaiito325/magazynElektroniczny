<?php
    function checkLogin(){
        if(isset($_SESSION['login'])){
            return true;
        }
        return false;
    }
    function checkPowerDifferentThan($x){
        if($_SESSION['power'] != '0'){
            return true;
        }
        return false;
    }
?>