<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

class TransactionExport
{
    public function __construct(
        private Collection $transactions,
        private bool $groupByBranch = false,
    ) {}

    public function download(string $filename)
    {
        $writer = new Writer();
        $writer->openToBrowser($filename);

        $headerStyle = (new Style())
            ->setFontBold()
            ->setFontSize(11);

        if ($this->groupByBranch) {
            $grouped = $this->transactions->groupBy(fn (Transaction $trx) => $trx->branch->name);

            foreach ($grouped as $branchName => $branchTransactions) {
                $writer->addRow(Row::fromValues([strtoupper($branchName)], $headerStyle));
                $writer->addRow(Row::fromValues([], (new Style())->setFontSize(8)));

                $this->writeTransactionRows($writer, $branchTransactions, $headerStyle);
            }
        } else {
            $this->writeTransactionRows($writer, $this->transactions, $headerStyle);
        }

        $writer->close();
    }

    private function writeTransactionRows(Writer $writer, Collection $transactions, Style $headerStyle): void
    {
        $this->writeHeader($writer, $headerStyle);

        foreach ($transactions as $trx) {
            $writer->addRow(Row::fromValues([
                $trx->code,
                $trx->transaction_date->format('d/m/Y'),
                $trx->user->name,
                '',
                '',
                '',
                '',
                $trx->total,
            ]));

            foreach ($trx->items as $item) {
                $writer->addRow(Row::fromValues([
                    '',
                    '',
                    '',
                    $item->product_name,
                    $item->product_price,
                    $item->quantity,
                    $item->subtotal,
                    '',
                ]));
            }

            $writer->addRow(Row::fromValues([]));
        }
    }

    private function writeHeader(Writer $writer, Style $headerStyle): void
    {
        $writer->addRow(Row::fromValues([
            'No Transaksi',
            'Tanggal',
            'Kasir',
            'Produk',
            'Harga Jual',
            'Qty',
            'Subtotal',
            'Total',
        ], $headerStyle));
    }
}
