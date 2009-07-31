<?php


/**
 * This class adds structure of 'course_detail' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 07/19/09 20:03:11
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CourseDetailMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CourseDetailMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(CourseDetailPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CourseDetailPeer::TABLE_NAME);
		$tMap->setPhpName('CourseDetail');
		$tMap->setClassname('CourseDetail');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('DETAIL_DESCR', 'DetailDescr', 'LONGVARCHAR', true, null);

		$tMap->addColumn('FIRST_OFFERED', 'FirstOffered', 'DATE', false, null);

		$tMap->addColumn('LAST_OFFERED', 'LastOffered', 'DATE', false, null);

		$tMap->addForeignKey('COURSE_ID', 'CourseId', 'VARCHAR', 'course', 'ID', true, 9);

	} // doBuild()

} // CourseDetailMapBuilder
