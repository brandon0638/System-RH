<?php
require('fpdf.php');

class PDF extends FPDF
{
private function ComputeSemesterSummary($notes)
{
    $credits = 0;
    $weightedSum = 0.0;

    foreach ($notes as $note) {
        $credit = (int) ($note['credit'] ?? 0);
        $value = isset($note['note']) ? (float) str_replace(',', '.', $note['note']) : 0.0;

        $credits += $credit;
        $weightedSum += ($value * $credit);
    }

    $average = $credits > 0 ? ($weightedSum / $credits) : 0;

    return array(
        'credits' => $credits,
        'average' => number_format($average, 2, ',', ''),
        'result' => $average >= 10 ? 'P' : 'NV'
    );
}

// En-tête
function Header()
{
    // Logo
    $this->Image('tutorial/logo.png',10,6,30);

    // Année universitaire (right aligned, no border)
    $this->SetFont('Arial','',11);
    $this->Cell(0,10,'Annee universitaire 20xx-20xx',0,0,'R');

    $this->Ln(18);
}

function MainTitle()
{
    $this->SetFont('Arial','B',16);
    $this->SetTextColor(0,102,153);
    $this->Cell(0,12,"RELEVE DE NOTES ET RESULTATS",0,1,'C');
    $this->SetTextColor(0,0,0);
    $this->Ln(4);
}

function SectionTitle($title)
{
    $this->SetFont('Arial','B',12);
    $this->Cell(0,8,$title,0,1,'L');
}

function StudentInfo($student)
{
    $this->SectionTitle('Informations etudiant');
    $this->SetFont('Arial','',11);

    $rows = array(
        'Nom' => $student['nom'] ?? '',
        'Prenom' => $student['prenom'] ?? '',
        'Date de naissance' => $student['date_naissance'] ?? '',
        "Numero d'inscription" => $student['numero'] ?? '',
        'Inscrit en' => $student['etudie'] ?? ''
    );

    foreach ($rows as $label => $value) {
        $this->Cell(60,8,$label.' :',0,0,'L');
        $this->Cell(0,8,$value,0,1,'L');
    }

    $this->Ln(5);
}

function SemesterNotes($sectionTitle, $notes)
{
    $this->SetFont('Arial','B',11);
    $this->Cell(0,8,$sectionTitle,0,1,'L');

    // Table header
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,'UE',1,0,'C');
    $this->Cell(85,8,'Intitule',1,0,'C');
    $this->Cell(25,8,'Credits',1,0,'C');
    $this->Cell(25,8,'Note/20',1,0,'C');
    $this->Cell(30,8,'Resultat',1,1,'C');

    $this->SetFont('Arial','',10);

    foreach ($notes as $note) {
        $this->Cell(25,8,$note['ue'] ?? '',1,0,'C');
        $this->Cell(85,8,$note['matiere'] ?? '',1,0,'L');
        $this->Cell(25,8,$note['credit'] ?? '',1,0,'C');
        $this->Cell(25,8,$note['note'] ?? '',1,0,'C');
        $this->Cell(30,8,$note['resultat'] ?? '',1,1,'C');
    }

    $this->Ln(3);
}

function NotesSection($semestre1, $semestre2)
{
    $this->SectionTitle('Notes');

    $this->SemesterNotes('Semestre 1', $semestre1);

    $s1Summary = $this->ComputeSemesterSummary($semestre1);
    $this->SetFont('Arial','B',10);
    $this->Cell(110,8,'SEMESTRE 1',1,0,'L');
    $this->Cell(25,8,$s1Summary['credits'],1,0,'C');
    $this->Cell(25,8,$s1Summary['average'],1,0,'C');
    $this->Cell(30,8,$s1Summary['result'],1,1,'C');

    $this->Ln(3);

    $this->SemesterNotes('Semestre 2', $semestre2);

    $s2Summary = $this->ComputeSemesterSummary($semestre2);
    $this->SetFont('Arial','B',10);
    $this->Cell(110,8,'SEMESTRE 2',1,0,'L');
    $this->Cell(25,8,$s2Summary['credits'],1,0,'C');
    $this->Cell(25,8,$s2Summary['average'],1,0,'C');
    $this->Cell(30,8,$s2Summary['result'],1,1,'C');

    $this->Ln(5);
}

function GeneralResult($result)
{
    $this->SectionTitle('Resultat general');
    $this->SetFont('Arial','',11);

    $this->Cell(60,8,'Credits :',0,0);
    $this->Cell(0,8,$result['credit'] ?? '',0,1);

    $this->Cell(60,8,'Moyenne generale :',0,0);
    $this->Cell(0,8,$result['moyenne'] ?? '',0,1);

    $this->Cell(60,8,'Mention :',0,0);
    $this->Cell(0,8,$result['mention'] ?? '',0,1);

    $this->SetFont('Arial','B',12);
    $this->Cell(0,10,$result['admis'] ?? '',0,1,'L');
}

function BuildReport($student, $semestre1, $semestre2, $result)
{
    $this->MainTitle();
    $this->StudentInfo($student);
    $this->NotesSection($semestre1, $semestre2);
    $this->GeneralResult($result);
}

// Pied de page
function Footer()
{
    $this->SetY(-20);
    $this->SetFont('Arial','',10);

    $this->Cell(0,8,'Fait a Antananarivo, le '.date('d/m/Y'),0,1,'R');
    $this->Cell(0,8,"Le Recteur de l'IT University",0,1,'R');

    $this->SetFont('Arial','I',8);
    $this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}