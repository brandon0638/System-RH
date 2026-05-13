<?php

namespace App\Libraries;

class ProgrammePdf
{
    public function build(array $data): string
    {
        require_once APPPATH . 'Libraries/fpdf/fpdf.php';

        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->encode($data['titre']), 0, 1);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $this->encode('Utilisateur: ' . $data['user']['nom']), 0, 1);
        $pdf->Cell(0, 7, $this->encode('Objectif: ' . $data['targetKg'] . ' kg'), 0, 1);

        $this->renderTable($pdf, 'Regimes recommandes', $data['regimes']);
        $this->renderTable($pdf, 'Sports recommandes', $data['sports']);

        return $pdf->Output('S');
    }

    private function renderTable(\FPDF $pdf, string $title, array $rows): void
    {
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, $this->encode($title), 0, 1);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(243, 239, 230);
        $pdf->Cell(90, 7, $this->encode('Nom'), 1, 0, 'L', true);
        $pdf->Cell(40, 7, $this->encode('Variation (g/j)'), 1, 0, 'L', true);
        $pdf->Cell(50, 7, $this->encode('Duree (jours)'), 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 11);

        if (!$rows) {
            $pdf->Cell(0, 7, $this->encode('Aucun resultat.'), 1, 1);
            return;
        }

        foreach ($rows as $row) {
            $pdf->Cell(90, 7, $this->encode((string) $row['nom']), 1, 0);
            $pdf->Cell(40, 7, $this->encode((string) $row['variation_poids_grammes']), 1, 0);
            $pdf->Cell(50, 7, $this->encode((string) $row['jours_necessaires']), 1, 1);
        }
    }

    private function encode(string $text): string
    {
        $converted = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
        return $converted !== false ? $converted : $text;
    }
}
