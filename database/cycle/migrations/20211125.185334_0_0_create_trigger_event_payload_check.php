<?php

use Cycle\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class OrmDefault9ad6069564d4b7abde89765793a82029 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        if (config('database.connections.mysql')) {
            DB::unprepared('drop trigger if exists trigger_event_payload_check');
            DB::unprepared("
                create trigger trigger_event_payload_check before insert on events
                    for each row
                begin
                    declare msg varchar(128);
                    if (not JSON_VALID(new.payload)) then
                        set msg = concat('trigger_event_payload_check: Trying to insert invalid payload in events: ', LEFT(new.payload, 10), ' (showing 10 first characters)');
                        signal sqlstate '45000' set message_text = msg;
                    end if;
                end;"
            );
        }
    }

    public function down()
    {
        if (config('database.connections.mysql')) {
            DB::unprepared('drop trigger if exists trigger_event_payload_check');
        }
    }
}
