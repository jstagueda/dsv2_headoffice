<?php
echo number_format(((float)trim(str_replace(',', '', $_GET['value_to_be_formatted']))), 2, '.', ','); 




