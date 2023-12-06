<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

error_reporting(E_ERROR | E_PARSE);

if ($_SERVER['REQUEST_METHOD'] != 'POST') { 
    header("Location: ./" );
    die();
}

require_once __DIR__.'/functions.php';

use Firebase\JWT\JWT;

if (empty($_POST['name'])) {
	$result = [
		'error' => 'Vui lòng nhập tên'
	];
	echo json_encode($result);
	die();
}

$name = $_POST['name'];
$phone = $_POST['phone'] ?? '';

$phone_trimmed = trim_phone($phone);

$template = $_POST['template'];
$cols = $config[$template]['cols'];

$spreadsheet_json = get_spreadsheet_data($template);
$spreadsheet_data = json_decode($spreadsheet_json)->values;

foreach ($spreadsheet_data as $index => $contestant_info) {
	$search_phone = $contestant_info[$cols['Số điện thoại']] ?? '';
	if (
		compare_name($name, $contestant_info[$cols['Họ và tên']])
		&& compare_phone($phone_trimmed, $search_phone)
	) {
		$result = [
			'success' => [
				'template' => $template,
				'headers'=> [
					'STT',
					'Họ và tên',
					'Số điện thoại',
					'Email'
				],
				'params' => [
					$contestant_info[$cols['STT']] ?? '',
					$contestant_info[$cols['Họ và tên']] ?? '',
					$contestant_info[$cols['Số điện thoại']] ?? '',
					$contestant_info[$cols['Email']] ?? '',
				],
				'additional_info' => [
					$contestant_info[$cols['Học bổng']] ?? '',
					$contestant_info[$cols['Date']] ?? '',
					$contestant_info[$cols['Thời hạn áp dụng']] ?? '',
				]
			]
		];

		$template_jwt = JWT::encode($result, $config['api_key'], 'HS256');
		$template_url = '<a target="_blank" class="btn btn-success btn-sm" href="download.php?jwt='.$template_jwt.'"><i class="bi bi-download"></i></a>';

		$result['success']['headers'][] = 'Tải Học bổng';
		$result['success']['params'][] = $template_url;

		echo json_encode($result);
		die();
	}
}

$result = [
	'error' => 'Không tìm thấy thông tin',
];
echo json_encode($result);
die();

// print_r($spreadsheet_data);

