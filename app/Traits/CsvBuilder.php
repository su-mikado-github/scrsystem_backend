<?php
namespace App\Traits;

trait CsvBuilder {
    public function write_csv_record($fp, $record) {
        mb_convert_variables('SJIS', 'UTF-8', $record);
        fputcsv($fp, $record);
    }

    public function write_csv($path, array $header, callable $record_builder) {
        $count = 0;

        $fp = fopen($path, 'w');
        try {
            // CSV列ヘッダーの出力
            if (count($header) > 0) {
                $csv_header = $header;
                mb_convert_variables('SJIS', 'UTF-8', $csv_header);
                fputcsv($fp, $csv_header);
            }

            // CSV列データの出力
            while (($record = $record_builder($count)) !== false) {
                $this->write_csv_record($fp, $record);
                $count ++; // 出力レコード数
            }
        }
        catch (\Exception $ex) {
            logger()->error($ex);
            return false;
        }
        finally {
            fclose($fp);
        }

        return $count;
    }

    public function stateful_write_csv($path, $state, array $header, callable $record_builder) {
        $count = 0;

        $fp = fopen($path, 'w');
        try {
            // CSV列ヘッダーの出力
            if (count($header) > 0) {
                $csv_header = $header;
                mb_convert_variables('SJIS', 'UTF-8', $csv_header);
                fputcsv($fp, $csv_header);
            }

            $record_writer = function($record) use($fp) {
                $this->write_csv_record($fp, $record);
            };

            // CSV列データの出力
            while ((list($state, $count) = $record_builder($state, $count, $record_writer)) !== false) {
                ;
            }
        }
        catch (\Exception $ex) {
            logger()->error($ex);
            return false;
        }
        finally {
            fclose($fp);
        }

        return $count;
    }
}
