<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Status;

class StatusFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {

    $status_array = [
      ['code' => 'APPR', 'desc' => 'Approved', 'notes' => 'Available only if the current purchase order status is Waiting for Approval (WAPPR), In Progress (INPRG), or Pending Revision (PNDREV).'],
      ['code' => 'CAN', 'desc' => 'Canceled', 'notes' => 'Available if the purchase order status is Waiting for Approval (WAPPR), Approved (APPR), In Progress (INPRG), or Pending Revision (PNDREV). A Cancel PO transaction is sent to the supplier upon cancellation of a purchase order.'],
      ['code' => 'CLOSE', 'desc' => 'Closed', 'notes' => 'Indicates that all of the line items for a purchase order were received. After a purchase order is closed, it is stored as a history record that cannot be modified. The Closed (CLOSE) status is not available if the purchase order is in Pending Revision (PNDREV) status.'],
      ['code' => 'INPRG', 'desc' => 'In Progress', 'notes' => 'Indicates that a purchase order is being revised, and the associated receipts and invoices are not to be processed. The HOLD status remains until the revision to the purchase order is approved.'],
      ['code' => 'PNDREV', 'desc' => 'Pending Revision', 'notes' => 'Indicates that a purchase order is being created and is not ready for approval.'],
      ['code' => 'REVISD', 'desc' => 'Revised', 'notes' => 'Indicates that a purchase order is being revised. A purchase order can be revised only if the status of the purchase order is Approved (APPR) or In Progress (INPRG). The PNDREV status is automatically assigned to a purchase order that is being revised.'],
      ['code' => 'WAPPR', 'desc' => 'Waiting for Approval', 'notes' => 'The default status for a purchase order when you create it. Some default fields are read-only in this status.'],

    ];
    
    foreach ($status_array as $stat)
    {
      $status = new Status;
      $status->setStatusCode($stat['code']);
      $status->setStatusDesc($stat['desc']);
      $status->setStatusNotes($stat['notes']);
      $manager->persist($status);
    }

    $manager->flush();
  }
}
