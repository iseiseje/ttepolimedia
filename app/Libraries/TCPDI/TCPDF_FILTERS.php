<?php
namespace TCPDI;

/**
 * TCPDF_FILTERS
 *
 * This class provides methods for decoding PDF streams with various filters.
 * This is a minimal version for use with tcpdi_parser.
 */
class TCPDF_FILTERS {
    /**
     * Return available filters.
     */
    public function getAvailableFilters() {
        return array('ASCIIHexDecode', 'ASCII85Decode', 'LZWDecode', 'FlateDecode', 'RunLengthDecode', 'CCITTFaxDecode', 'JBIG2Decode', 'DCTDecode', 'JPXDecode', 'Crypt');
    }

    /**
     * Decode a stream using the specified filter.
     */
    public function decodeFilter($filter, $data) {
        switch ($filter) {
            case 'FlateDecode':
                return @gzuncompress($data);
            case 'ASCIIHexDecode':
                return $this->decodeAsciiHex($data);
            case 'ASCII85Decode':
                return $this->decodeAscii85($data);
            case 'LZWDecode':
                // LZW not implemented in this minimal version
                return $data;
            case 'RunLengthDecode':
                // RunLength not implemented in this minimal version
                return $data;
            default:
                // Other filters not implemented
                return $data;
        }
    }

    private function decodeAsciiHex($input) {
        $output = '';
        $isOdd = true;
        $hex = '';
        $len = strlen($input);
        for ($i = 0; $i < $len; $i++) {
            $c = $input[$i];
            if ($c == '>') {
                break;
            }
            if (preg_match('/[0-9A-Fa-f]/', $c)) {
                if ($isOdd) {
                    $hex = $c;
                } else {
                    $output .= chr(hexdec($hex . $c));
                }
                $isOdd = !$isOdd;
            }
        }
        if (!$isOdd) {
            $output .= chr(hexdec($hex . '0'));
        }
        return $output;
    }

    private function decodeAscii85($input) {
        $output = '';
        $state = 0;
        $ch = array();
        $len = strlen($input);
        for ($i = 0; $i < $len; $i++) {
            $c = $input[$i];
            if ($c == '~') {
                break;
            }
            if (preg_match('/\s/', $c)) {
                continue;
            }
            if ($c == 'z' && $state == 0) {
                $output .= str_repeat(chr(0), 4);
                continue;
            }
            if ($c < '!' || $c > 'u') {
                continue;
            }
            $ch[$state++] = ord($c) - 33;
            if ($state == 5) {
                $state = 0;
                $r = 0;
                for ($j = 0; $j < 5; $j++) {
                    $r = $r * 85 + $ch[$j];
                }
                $output .= chr($r >> 24) . chr(($r >> 16) & 0xFF) . chr(($r >> 8) & 0xFF) . chr($r & 0xFF);
            }
        }
        if ($state) {
            $r = 0;
            for ($j = 0; $j < $state; $j++) {
                $r = $r * 85 + $ch[$j];
            }
            for ($j = $state; $j < 5; $j++) {
                $r = $r * 85 + 84;
            }
            for ($j = 0; $j < $state - 1; $j++) {
                $output .= chr($r >> (24 - $j * 8) & 0xFF);
            }
        }
        return $output;
    }
} 