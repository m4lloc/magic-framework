<?php

  use PHPUnit\Framework\TestCase;

  final class OrmTest extends TestCase {

      public function setUp() : void {
        (new \M\Orm())
          ->create()
          ->database('orm_test')
          ->exec();

        (new \M\Orm(['database' => 'orm_test']))
          ->create()
          ->table('user')
          ->columns(
            ['id', 'int', 20, 'primary'],
            ['name', 'varchar', 255]
          )
          ->exec();
          \M\Orm::query("INSERT INTO user (id, name) VALUES(1, 'Test 1')");
          \M\Orm::query("INSERT INTO user (id, name) VALUES(2, 'Test 2')");
      }

      public function tearDown() : void {
        (new \M\Orm())
          ->drop()
          ->database('orm_test')
          ->exec();
      }
      
      public function testConstruct() {
        $this->assertInstanceOf(\M\Orm::class, new \M\Orm());
      }

      public function testConstructWithWrongCredentials($config=[]) {
        $this->expectException(\M\OrmConnectionException::class);
        new \M\Orm([
          'username' => 'non-existing-user',
          'password' => 'wrong-password'
        ]);
      }

      public function testConstructWithNonExistingDatabase() {
        $this->expectException(\M\OrmConnectionException::class);
        new \M\Orm([
          'database' => 'non-existing-database'
        ]);
      }

      public function testSetColumnValue() {
        $o = new \M\Orm();
        $o->name = 'value';

        $this->assertEquals($o->name, 'value');
      }

      public function testGetColumnValue() {
        $o = new \M\Orm();
        $o->name = 'value';

        $this->assertEquals($o->name, 'value');
      }

      public function testCallingSelectMethod() {
        $o = new \M\Orm();
        $o->select('value');

        $this->assertEquals($o->getComponents(), ['select' => ['value']]);
      }

      public function testCallingBuildQuery() {
        $r = (new \M\Orm())
          ->select('name')
          ->from('user')
          ->build();

        $this->assertEquals(
          $r,
          'SELECT name FROM `user`'
        );
      }

      public function testBuildingUnknownComponent() {
        $this->expectException(\M\OrmBuilderComponentNotImplementedException::class);
        (new \M\Orm())
          ->nonExistingComponent('name')
          ->build();
      }

      public function testManuallyWrittenQuery() {
        $r = \M\Orm::query('SELECT * FROM user WHERE id = :id LIMIT 1', [
          'id' => 1
        ]);
        $this->assertTrue(is_array($r));
        $this->assertCount(1, $r);
      }
      
      public function testExecutingAQuery() {
        $r = (new \M\Orm())
          ->exec(
            'SELECT * FROM user WHERE id = :id LIMIT 1',
            ['id' => 1]
          );
        $this->assertTrue(is_array($r));
        $this->assertCount(1, $r);
      }

      public function testFindAllRecords() {
        $r = (new \M\Orm())->from('user')->find();
        $this->assertTrue(is_array($r));
        $this->assertCount(2, $r);
      }
      
      public function testFindOneRecord() {
        $r = (new \M\Orm())->from('user')->findOne();
        $this->assertInstanceOf(User::class, $r);
      }

      public function testUpdatingRecordsWhileCallingSave() {
        $u = new User();
        $u->id = 1;
        $u->name = 'Name changed';
        $u->save();

        $r = (new \M\Orm())
          ->from('user')
          ->where([
            'id' => $u->id
          ])
          ->findOne();

        $this->assertEquals(
          $u->name,
          $r->name
        );
      }

      public function testSaveUpdateFailedSoSkipToInsert() {
        $u = new User();
        $u->id = 3;
        $u->name = 'Test 3';
        $u->save();

        $r = (new \M\Orm())
          ->from('user')
          ->where([
            'id' => $u->id
          ])
          ->findOne();
        
        $this->assertEquals(
          $u->name,
          $r->name
        );
      }

      public function testSaveInsertWithoutPrimaryKey() {
        $u = new User();
        $u->name = 'Test 3';
        $u->save();

        $r = (new \M\Orm())
          ->from('user')
          ->where([
            'name' => $u->name
          ])
          ->findOne();
        
        $this->assertEquals(
          $u->name,
          $r->name
        );
      }

      public function testDeletingARecord() {
        $r = (new \M\Orm())->from('user')->find();
        $this->assertCount(2, $r);

        $r = (new \M\Orm())
          ->from('user')
          ->where([
            'id' => 1
          ])
          ->remove();

        $r = (new \M\Orm())
          ->from('user')
          ->where([
            'id' => 1
          ])
          ->findOne();
        $this->assertFalse($r);

        $r = (new \M\Orm())->from('user')->find();
        $this->assertCount(1, $r);
      }
  }