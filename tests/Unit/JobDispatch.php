<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendBulkQueueEmail;

class JobDispatch extends TestCase
{
  public function TestQueueDispatchJob()
  {
    Queue::fake();
    SendBulkQueueEmail::dispatchNow();
    Queue::assertPushed(SendBulkQueueEmail::class);
  }
}
