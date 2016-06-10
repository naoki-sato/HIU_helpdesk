<?php

return [

    // sometimesはフィールドが入力配列の中に存在する場合のみ、バリデーションを実行
    'rules' => [
        'item_name'         => 'sometimes|required|string',
        'place_id'          => 'sometimes|required|numeric',
        'staff_id'          => 'sometimes|required|numeric',
        // 'student_id'        => 'sometimes|required|numeric',
        'student_no'        => 'sometimes|required|string',
        'delivery_staff_id' => 'sometimes|required|numeric',
        'export'            => 'sometimes|required|numeric',
        'start_number'      => 'sometimes|required|numeric',
        'end_number'        => 'sometimes|required|numeric',
        'year'              => 'sometimes|required|numeric',



        'staff_name'        => 'sometimes|required|max:255',
        'staff_no'          => 'sometimes|required|max:255|unique:users',
        'password'          => 'sometimes|required|confirmed|min:6',
        'role'              => 'sometimes|required|string',
        'phone_no'          => 'sometimes|string',

    ],




];