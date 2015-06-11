<?php
define('AJAX_SCRIPT', true);
define('NO_DEBUG_DISPLAY', true);

header("Content-Type: application/json; charset=UTF-8");

require_once("../../../../config.php");
require_once('rgrade_lib.php');

$id = required_param('id', PARAM_INT); // course_module ID
if (($cm = get_coursemodule_from_id('rcontent', $id)) === false) {
    print_error('Course Module ID was incorrect');
}

if (($course = $DB->get_record('course', array('id' => $cm->course))) === false) {
    print_error('Course is misconfigured');
}

if (($rcontent = $DB->get_record('rcontent', array('id' => $cm->instance))) === false) {
    print_error('Course module is incorrect');
}

$bookid = $rcontent->bookid;
$courseid = $course->id;

$book = rgrade_get_book_from_course($courseid, $bookid);
if (!$book) {
	rgrade_json_error('Book not valid');
}

$data = array();

$data['students'] = array();


$students = rgrade_get_all_students($courseid);
foreach ($students as $student) {
	$sid = (int) $student->id;

	$data['students'][] = array(
		'id' => $sid,
		'lastname' => $student->lastname,
		'firstname' => $student->firstname);
}

$data['groups'] = array();

$groups = rgrade_get_groups_studentsid($courseid);
foreach ($groups as $group) {

	$gid = (int) $group->groupid;

	if(!isset($data['groups'][$gid])){

		$data['groups'][$gid] = array(
			'id' => $gid,
			'name' => $group->groupname,
	 		'studentids' => array());
	}

	$data['groups'][$gid]['studentids'][] = (int) $group->userid;
}
$data['groups'] = array_values($data['groups']);


$data['scores'] = $ENUM_SCORES;
$data['status'] = $ENUM_STATUS;
$data['book'] = array('id' => $book->id, 'name' => $book->name, 'units' => array());

$activities = rgrade_get_recordset_activities($book->id);
foreach ($activities as $activity) {
	$uid = (int) $activity->unitid;

	if(!isset($data['book']['units'][$uid])){
		$data['book']['units'][$uid] = array(
			'id' => $uid,
			'code' => $activity->unitcode,
			'name' => $activity->unitname);
		$data['book']['units'][$uid]['activities'] = array();
	}

	$data['book']['units'][$uid]['activities'][] = array(
			'id' => (int) $activity->id,
			'code' => $activity->code,
			'name' => $activity->name);
}

$data['book']['units'] = array_values($data['book']['units']);

echo json_encode($data);
