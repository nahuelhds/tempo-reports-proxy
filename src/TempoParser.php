<?php
namespace CristalMedia;

use \Ixudra\Curl\CurlService;

class TempoParser
{
    /**
     * Curl service
     *
     * @var \Ixudra\Curl\CurlService
     */
    private $curl;

    /**
     * Curl req builder
     *
     * @var \Ixudra\Curl\Builder
     */
    private $req;

    /**
     * Name for the temporary CSV file
     *
     * @var string
     */
    private $filename_csv;

    /**
     * Name for the temporary XLS file
     *
     * @var string
     */
    private $filename_xls;

    /**
     * XLS data
     *
     * @var string
     */
    private $xls;

    /**
     * CSV data
     *
     * @var string
     */
    private $csv;

    /**
     * Init the object with the general configurations
     */
    public function __construct($uri, $token)
    {
        $this->curl = new CurlService();
        $this->req = $this->curl->to($uri)->returnResponseObject();
        $this->reqOptions = [
            "tempoApiToken" => $token,
            'addIssueDetails' => true,
            'addBillingInfo' => true,
            "diffOnly" => false,
            "format" => "excel",
        ];

        $ahora = new \DateTime();
        $this->filename_csv = $ahora->format('U') . '.csv';
        $this->filename_xls = $ahora->format('U') . '.xls';
    }

    /**
     * Delete the temporary files
     */
    public function __destruct()
    {
        $this->deleteTempFiles();
    }

    /**
     * CSV string generated in the process
     *
     * @return string
     */
    public function getCsv()
    {
        return $this->csv;
    }

    /**
     * XLS string obtained from the server
     *
     * @return string
     */
    public function getXls()
    {
        return $this->xls;
    }

    /**
     * Set the range dates in the query
     *
     * If $from is null, then the first day of the month will be set
     * If $to is null, the current day will be set
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public function setDateRange(string $dateFrom = null, string $dateTo = null)
    {
        $fromDate = new \DateTime($dateFrom);
        $toDate = new \DateTime($dateTo);
        $this->reqOptions += [
            'dateFrom' => $fromDate->format($dateFrom ? 'Y-m-d' : 'Y-m-01'),
            "dateTo" => $toDate->format('Y-m-d'),
        ];
    }

    /**
     * Triggers the curl request and gets the XLS string from the server
     *
     * @return string   The XLS string
     * @throws \RuntimeException
     */
    public function getData()
    {
        $this->req->withData($this->reqOptions);

        $res = $this->req->get();

        // If the response is not OK
        if ($res->status !== 200) {
            $err = \simplexml_load_string($res->content);
            throw new \RuntimeException("The server returned the following error message: "
                . $err->message . ". Query: " . $err->detail);

            return false;
        }

        $this->xls = $res->content;
        return $this->xls;
    }

    /**
     * Creates a CSV file based on the given $xlsString
     *
     * @param string $xlsString
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function createCsvFromXlsString($xlsString)
    {
        // Create the XLS file that we'll use later
        \file_put_contents($this->filename_xls, $xlsString);

        try {
            // XLS Read
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($this->filename_xls);

            // CSV write
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
            $writer->setUseBOM(true);
            $writer->save($this->filename_csv);
    
            // Saves the CSV as string
            $this->csv = file_get_contents($this->filename_csv);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $err) {

            $this->deleteTempFiles();
            throw new \RuntimeException("The XLS file couldn't be created becaus the given string is not compatible with the expected OLE format.");
        }
    }

    /**
     * Erase the temporary files
     *
     * @return void
     */
    private function deleteTempFiles()
    {
        @unlink($this->filename_csv);
        @unlink($this->filename_xls);
    }
}
