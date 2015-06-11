<?php

function print_search_form($courseid, $bookid) {

    $students = rgrade_get_all_students($courseid);
    foreach ($students as $student) {
        $sid = (int) $student->id;

        $data['students'][] = array(
            'id' => $sid,
            'lastname' => $student->lastname,
            'firstname' => $student->firstname);
    }


    $data['groups'] = array();




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

    echo '<div id="rgrade_search"><div class="in clearfix">
        <fieldset id="report_extended_users_fs1">
        <label>
            <span>'.get_string('group').'</span>
            <select name="groupid" id="field_groupid">
            <option value=""><?php echo get_string('all');?></option>';
    $groups = rgrade_get_groups_studentsid($courseid);
    foreach ($groups as $group) {
        $gid = (int) $group->groupid;
        if(!isset($data['groups'][$gid])){

            $data['groups'][$gid] = array(
                'id' => $gid,
                'name' => $group->groupname,
                'studentids' => array());
            echo '<option value="'. $gid.'">'.$group->groupname.'</option>';
        }


        $data['groups'][$gid]['studentids'][] = (int) $group->userid;
    }
    $data['groups'] = array_values($data['groups']);

    echo '
            {{/each}}
            </select>
        </label>
        </fieldset>

        <fieldset id="report_extended_users_fs2">
        <label>
            <span>{{I18n "Students"}}</span>
            <select name="studentid[]" multiple="true" id="field_studentid" size="4">
            </select>
        </label>
        </fieldset>

        <fieldset id="report_extended_units_fs">
        <label>
            <span>{{I18n "Units"}}</span>
            <select name="unitid[]" multiple="true" id="field_unitid" size="4">
            {{#each book.units}}
            <option value="{{id}}">{{name}} ({{code}})</option>
            {{/each}}
            </select>
        </label>
        </fieldset>

        <fieldset id="report_extended_date_fs">
        <label>
            <span>{{I18n "Begin"}}</span>
            <input type="text" class="datepicker" name="begin"/>
        </label>
        <label>
            <span>{{I18n "End"}}</span>
            <input type="text" class="datepicker" name="end"/>
        </label>
        </fieldset>

        <fieldset id="report_extended_state_fs">
        <label>
            <span>{{I18n "State"}}</span>
            <select name="stateid" id="field_stateid">
            <option value="">{{I18n "All states"}}</option>
            {{#each status}}
            <option value="{{.}}">{{I18n .}}</option>
            {{/each}}
            </select>
        </label>

        <input id="submit1" type="button" name="filter" value="{{I18n "Filter"}}" class="button filter"/>
        </fieldset>

        </div>
        </div><!--/rgrade_search -->
        </div><!-- /rgrade_search_wrapper -->

        <div class="filter_export_print_wrapper clearfix hide-print">
        <fieldset id="report_extended_score_fs">
        <label>
            <select name="scoreid" id="field_scoreid">
            {{#each scores}}
            <option value="{{.}}">{{I18n .}}</option>
            {{/each}}
            </select>
        </label>
        <input id="submit2" type="button" name="change_score" value="{{I18n "Change"}}" class="button change"/>
        </fieldset>

        <div id="back_print_excel">
        <input id="submit_print" type="button" name="print" value="{{I18n "Print"}}" class="button print"/>
        <input id="submit_excel" type="button" name="export" value="{{I18n "Excel Export"}}" class="button excel"/>
        <a id="button_book" href="#view=book" title=""  class="button btn btn-info">{{I18n "Book data"}}</a>
        <input id="submit_back" type="button" name="back" value="{{I18n "Back"}}" class="button back"/>
        </div>

        </div><!-- /filter_export_print_wrapper -->';
}