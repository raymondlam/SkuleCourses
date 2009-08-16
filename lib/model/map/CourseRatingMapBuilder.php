<?php


/**
 * This class adds structure of 'course_rating_data' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 08/16/09 10:21:02
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CourseRatingMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CourseRatingMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CourseRatingPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CourseRatingPeer::TABLE_NAME);
		$tMap->setPhpName('CourseRating');
		$tMap->setClassname('CourseRating');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'user', 'ID', true, null);

		$tMap->addForeignKey('FIELD_ID', 'FieldId', 'INTEGER', 'rating_field', 'ID', true, null);

		$tMap->addForeignPrimaryKey('COURSE_INS_ID', 'CourseInsId', 'INTEGER' , 'course_instructor_assoc', 'ID', true, null);

		$tMap->addColumn('RATING', 'Rating', 'TINYINT', true, null);

		$tMap->addColumn('INPUT_DT', 'InputDt', 'TIMESTAMP', true, null);

	} // doBuild()

} // CourseRatingMapBuilder
