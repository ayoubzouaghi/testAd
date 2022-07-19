<?php
//phpinfo();
$d = ldap_connect("ldaps://ad.lookiimobile.com", 636);
var_dump(ldap_bind($d, 'kamoun@ad.lookiimobile.com', 'Farésfaresfares1996*'));
