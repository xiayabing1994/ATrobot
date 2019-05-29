<?php

function mkCardNo(){
    return str_shuffle(md5(rand(10000,9999)).time());
}
