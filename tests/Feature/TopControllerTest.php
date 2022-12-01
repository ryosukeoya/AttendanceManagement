<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TopControllerTest extends TestCase
{
    use DatabaseMigrations;

    // TODO Refactor
    private const TestingSeederClassName = 'TestingDatabaseSeeder';

    /**
     * 未登録時のユーザーがapi_attendance_recordを叩いた時のレスポンスは正しいか
     *
     * @return void
     */
    public function test_api_attendance_record_unregistered()
    {
        $this->seed(self::TestingSeederClassName);

        $attendanceUnregisteredUser = User::find(1);
        $res = $this->actingAs($attendanceUnregisteredUser)->get(route('api_attendance_record'));
        $res->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['attendanceStatus' => 0]);
    }

    /**
     * 始業登録済みのユーザーがapi_attendance_recordを叩いた時のレスポンスは正しいか
     *
     * @return void
     */
    public function test_api_attendance_record_started()
    {
        $this->seed(self::TestingSeederClassName);

        $startedUser = User::find(2);
        $res = $this->actingAs($startedUser)->get(route('api_attendance_record'));
        $res->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['attendanceStatus' => 1]);
    }

    /**
     * 始業登録済み、終業登録済みのユーザーがapi_attendance_recordを叩いた時のレスポンスは正しいか
     *
     * @return void
     */
    public function test_api_attendance_record_ended()
    {
        $this->seed(self::TestingSeederClassName);

        $endedUser = User::find(3);
        $res = $this->actingAs($endedUser)->get(route('api_attendance_record'));
        $res->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['attendanceStatus' => 2]);
    }

    /**
     * 始業未登録、終業登録済みのユーザーがapi_attendance_recordを叩いた時のレスポンスは正しいか
     *
     * @return void
     */
    public function test_api_attendance_record_illegal()
    {
        $this->seed(self::TestingSeederClassName);

        $closingReportOnlyUser = User::find(4);
        $res = $this->actingAs($closingReportOnlyUser)->get(route('api_attendance_record'));
        $res->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['attendanceStatus' => 3]);
    }
}
