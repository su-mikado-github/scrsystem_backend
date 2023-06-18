<?php
namespace App\Traits;

trait CsvBuilder {
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
                mb_convert_variables('SJIS', 'UTF-8', $record);
                fputcsv($fp, $record);

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
}
