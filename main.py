import datetime
import mysql.connector
import os.path
import random

strUserName = os.getlogin()

meterReadsPath = 'C:\\Users\\' + strUserName + '\\Documents\\Meter-Reads-Website\\'

dataBaseName = 'MeterReadsWebsite'

facilities = {
    'facilityName': ['Apartment One', 'Penthouse'],
    'units': [5, 10]
}

startDate = datetime.datetime.today().strftime('%Y') + '-01-01'
today = datetime.datetime.today()

def generateMeterReads(facilityName, units):
    start = datetime.datetime.strptime(startDate, '%Y-%m-%d')
    days = (today - start).days

    facilityName = facilityName.replace(" ", "").lower()

    #stores all the individual meter reads to be inserted into the database
    #its a multidimensional array with individual reads for ele, nga, and water
    meterReads = [[], [], []]

    #used to make sure we continuously build on top of what the meter reads say
    meterValues = [[], [], []]

    #loops through the number of units and adds a starter value for each meter
    for array in meterValues:
        print(array)
        for i in range(units):
            array.append(0)

    #loops through each day and creates meter reads for that day
    for i in range(days):
        date = str((start+datetime.timedelta(days=i)).strftime('%Y-%m-%d'))

        for x in range(len(meterReads)):
            if x == 0:
                utility = 'ele'
            if x == 1:
                utility = 'nga'
            if x == 2:
                utility = 'water'

            #loops through the units to add a meter read for each unit
            for y in range(units):
                read = random.randint(0, 30)
                meterValues[x][y] += read
                print(y + 1)
                meterReads[x].append([facilityName, (y + 1), meterValues[x][y], utility, date])

    return meterReads

def postMeterReads(mydb, mycursor):
    #iterates through the facilities to create meter reads for them
    for i in range(len(facilities['facilityName'])):
        facilityName = facilities['facilityName'][i]
        units = facilities['units'][i]

        meterReads = generateMeterReads(facilityName, units)
        print(meterReads)
        #uploads the meter reads for water, nga, and ele
        for array in meterReads:
            sql = "INSERT INTO meterreads (facility, unit, meter_read, utility, date) VALUES (%s,%s,%s,%s,%s)"
            print("uploading those meter reads now")

            mycursor.executemany(sql, array)
            mydb.commit()

        print("Meter reads uploaded")

def connect():
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password=""
    )
    mycursor = mydb.cursor()

    return mydb, mycursor

def connectDB():
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database=dataBaseName
    )
    mycursor = mydb.cursor()
    return mydb, mycursor

def createTables(mydb, mycursor):
        mycursor.execute("CREATE TABLE meterReads (id INT AUTO_INCREMENT PRIMARY KEY,facility VARCHAR(255), unit VARCHAR(255), meter_read VARCHAR(255), utility VARCHAR(255), date VARCHAR(255))")
        mycursor.execute("CREATE TABLE stats (id INT AUTO_INCREMENT PRIMARY KEY, facility VARCHAR(255), stat VARCHAR(255), utility VARCHAR(255), value VARCHAR(255), date VARCHAR(255))")
        mydb.commit()

def checkDB(mydb, mycursor, dataBaseName):
    dataBaseExists = False

    mycursor.execute("SHOW DATABASES")

    # loops through the databases to check if they exists
    for x in mycursor:
        # print(x)
        if (dataBaseName.lower() in str(x).lower()):
            dataBaseExists = True
            print("DB Exists")
            #connects to the database
            mydb, mycursor = connectDB()

    if (dataBaseExists == False):
        # creats a database with the name of the facility
        mycursor.execute("CREATE DATABASE " + dataBaseName)
        mydb.commit()

        # connects to the database
        mydb, mycursor = connectDB()

        createTables(mydb, mycursor)

        print("DB Created")
    return mydb, mycursor

def clearMeterReads(mydb, mycursor):
    sql = "TRUNCATE TABLE meterreads"
    mycursor = mydb.cursor()
    mycursor.execute(sql)
    mydb.commit()
    print("Table truncated")

def start():
    #gets a connection to the main host that will contain the database
    mydb, mycursor = connect()

    #checks to see if the database exists, if not it creates it
    #if it does it returns the connection
    mydb, mycursor = checkDB(mydb, mycursor, dataBaseName)

    #clears the meter reads in the database
    clearMeterReads(mydb, mycursor)

    #calls a function that will generate and post meter reads to the database
    postMeterReads(mydb, mycursor)

start()