<?php
/*
XSLTranformer -- Class to transform XML files using XSL.
Legacy API shim kept for backward compatibility.
*/

class XSLTransformerPHP41 {
    function __construct()
    {
        call_user_func_array(array($this, 'XSLTransformerPHP41'), func_get_args());
    }

    var $xsl, $xml, $output, $error, $errorcode, $processor, $uri, $host, $port, $byJava;
    var $xslBaseUri;

    /* Constructor */
    function XSLTransformerPHP41()
    {
        $this->processor = new XSLTProcessor();
        $this->xslBaseUri = '';
    }

    function setXslBaseUri($uri)
    {
        $this->xslBaseUri = (string)$uri;
        return true;
    }

    /* Destructor */
    function destroy()
    {
        $this->processor = null;
    }

    /* transform method */
    function transform($xml, $xsl, &$error)
    {
        $error = '';
        if (!is_string($xml) || trim($xml) === '') {
            $error = 'Error: empty XML input';
            return false;
        }
        if (!is_string($xsl) || trim($xsl) === '') {
            $error = 'Error: empty XSL input';
            return false;
        }

        libxml_use_internal_errors(true);

        $xmlDoc = new DOMDocument();
        if (!@$xmlDoc->loadXML($xml)) {
            // Legacy WXIS payloads may come as ISO-8859-1 bytes labeled as UTF-8.
            $xmlUtf8 = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1,Windows-1252,UTF-8');
            $xmlUtf8 = preg_replace('/<\\?xml[^>]*encoding=[\"\'][^\"\']+[\"\'][^>]*\\?>/i', '<?xml version="1.0" encoding="UTF-8"?>', $xmlUtf8, 1);
            if (!@$xmlDoc->loadXML($xmlUtf8)) {
                $libxmlErrors = libxml_get_errors();
                $firstError = isset($libxmlErrors[0]) ? trim($libxmlErrors[0]->message) : 'invalid XML input';
                libxml_clear_errors();
                $error = 'Error: ' . $firstError;
                return false;
            }
        }

        $xslDoc = new DOMDocument();
        if (!@$xslDoc->loadXML($xsl)) {
            $xslUtf8 = mb_convert_encoding($xsl, 'UTF-8', 'ISO-8859-1,Windows-1252,UTF-8');
            $xslUtf8 = preg_replace('/<\\?xml[^>]*encoding=[\"\'][^\"\']+[\"\'][^>]*\\?>/i', '<?xml version="1.0" encoding="UTF-8"?>', $xslUtf8, 1);
            if (!@$xslDoc->loadXML($xslUtf8)) {
                $libxmlErrors = libxml_get_errors();
                $firstError = isset($libxmlErrors[0]) ? trim($libxmlErrors[0]->message) : 'invalid XSL input';
                libxml_clear_errors();
                $error = 'Error: ' . $firstError;
                return false;
            }
        }
        if ($this->xslBaseUri !== '') {
            $baseUri = $this->xslBaseUri;
            if (preg_match('/\/$/', $baseUri)) {
                $baseUri .= '__main__.xsl';
            }
            $xslDoc->documentURI = $baseUri;
        }

        libxml_clear_errors();

        $this->processor = new XSLTProcessor();
        if (!@$this->processor->importStylesheet($xslDoc)) {
            $error = 'Error: failed to import XSL stylesheet';
            return false;
        }

        $result = @$this->processor->transformToXML($xmlDoc);
        if ($result === false) {
            $error = 'Error: XSL transformation failed';
            return false;
        }

        $result = xml_utf8_decode($result);
        return $result;
    }
}
?>
