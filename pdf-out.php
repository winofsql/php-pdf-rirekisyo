<?php
// ************************************************
// セッションとキャッシュなし
// ************************************************
session_cache_limiter('nocache');
session_start();

require_once("print.php");

// ************************************************
// フォーマットデータ
// ************************************************
$url = "ri.json";
$file = @file_get_contents($url);
/*
$file = '{
  "format" : [ {
    "s" : 18,
    "t" : "履　歴　書",
    "type" : "text",
    "x" : 10,
    "y" : 3
  }, {
    "s" : 14,
    "t" : "令和　　年　　月　　日現在",
    "type" : "text",
    "x" : 95,
    "y" : 4
  }, {
    "h" : 170,
    "type" : "vline",
    "x" : 35,
    "y" : 115
  }, {
    "type" : "line",
    "w" : 150,
    "x" : 10,
    "y" : 38
  }, {
    "n" : 16,
    "p" : 10,
    "type" : "lines",
    "w" : 190,
    "x" : 10,
    "y" : 120
  }, {
    "h" : 40,
    "type" : "rect",
    "w" : 150,
    "x" : 10,
    "y" : 10
  }, {
    "h" : 55,
    "type" : "rect",
    "w" : 190,
    "x" : 10,
    "y" : 55
  }, {
    "h" : 170,
    "type" : "rect",
    "w" : 40,
    "x" : 10,
    "y" : 115
  }, {
    "h" : 170,
    "type" : "rect",
    "w" : 190,
    "x" : 10,
    "y" : 115
  }, {
    "f" : "man.png",
    "h" : 35,
    "type" : "image",
    "w" : 30,
    "x" : 167,
    "y" : 12
  } ]
}';
*/
if ( $file !== false ) {
	// 連想配列形式で返す
	$result = json_decode( $file, true );

	if ( $result == null ) {
		// テキストのサイズ変更
		$pdf->SetFont('ume-tmo3', '', 20);
		// テキストの色
		$pdf->SetTextColor(255, 0, 0);
		user_text( $pdf, 10, 10, 'データが存在しません' );
		$pdf->Output("test_output.pdf", "I");
		exit();
	}

}


$pdf->SetFont('ume-tmo3', '', 14);

$pdf->AddPage();

// ************************************************
// 印字コマンド実行
// ************************************************
foreach( $result['format'] as $obj ) {

	if ( $obj['type'] == 'image' ) {
		$pdf->Image($obj['f'], $obj['x'],$obj['y'],$obj['w'],$obj['h']);
		continue;
	}

	if ( $obj['type'] == 'text' ) {
		$pdf->SetFont('ume-tmo3', '', $obj['s']);
		user_text( $pdf, $obj['x'],$obj['y'], $obj['t'] );
		continue;
	}

	if ( $obj['type'] == 'rect' ) {
		$pdf->Rect($obj['x'],$obj['y'],$obj['w'],$obj['h']);
		continue;
	}

	if ( $obj['type'] == 'line' ) {
		$pdf->Line($obj['x'],$obj['y'],$obj['x']+$obj['w'],$obj['y']);
		continue;
	}

	if ( $obj['type'] == 'vline' ) {
		$pdf->Line($obj['x'],$obj['y'],$obj['x'], $obj['y']+$obj['h']);
		continue;
	}

	if ( $obj['type'] == 'lines' ) {

		$row = $obj['y'];
		
		for( $i = 0; $i < $obj['n']; $i++ ) {
			$pdf->Line($obj['x'], $row, $obj['x']+$obj['w'], $row);
			$row += $obj['p'];
		}

		continue;
	}

}


// ブラウザへ PDF を出力します
$pdf->Output("test_output.pdf", "I");
?>
