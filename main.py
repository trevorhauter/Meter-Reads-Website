import os, glob
import pymysql
import datetime
from datetime import datetime
from datetime import date
import dateutil.relativedelta
import mysql.connector
import pandas as pd
import time
import win32com.client
import os.path
from os import path

strUserName = os.getlogin()
strUserName = "Work"

greenAcresSaveFilePath = 'C:\\Users\\' + strUserName + '\\Documents\\Green Acres\\NextCentury\\'

williamsSaveFilePath = 'C:\\Users\\' + strUserName + '\\Documents\\CCP Invoices\\DCAP\\'

fileFound = False

today = date.today()
todaysDate = today.strftime("%Y-%m-%d")

# checks if outlook is running, if not it opens it
def outlook_is_running():
    import win32ui
    try:
        win32ui.FindWindow(None, "Microsoft Outlook")
        return True
    except win32ui.error:
        return False

def downloadGreenAcresStatements():
    global fileFound

    outlook = win32com.client.Dispatch("Outlook.Application")
    namespace = outlook.GetNamespace("MAPI")
    statementFolder = namespace.Folders['data@muc-corp.com'].Folders['Green Acres']
    # Folder = outlook.Folders[0]
    print(statementFolder)

    for email in statementFolder.Items:
        # print(email.Subject)
        statement = email.Attachments.Item(1)
        # print(statement)
        if path.exists(greenAcresSaveFilePath + str(statement)) == False:
            statement.SaveAsFile(os.path.join(greenAcresSaveFilePath, str(statement)))

        if todaysDate in str(statement):
            fileFound = True
            print("Todays file found - " + str(statement))

    if fileFound == True:
        return  True
    else:
        return False

def downloadWilliamsStatements():
    global fileFound

    outlook = win32com.client.Dispatch("Outlook.Application")
    namespace = outlook.GetNamespace("MAPI")
    statementFolder = namespace.Folders['data@muc-corp.com'].Folders['DCAP']
    # Folder = outlook.Folders[0]
    print(statementFolder)

    for email in statementFolder.Items:
        # print(email.Subject)
        statement = email.Attachments.Item(1)
        # print(statement)
        # if path.exists(williamsSaveFilePath + str(statement)) == False:
        #     statement.SaveAsFile(os.path.join(williamsSaveFilePath, str(statement)))
        if not glob.glob(williamsSaveFilePath + str(statement)[0:29] + "*.csv"):
            statement.SaveAsFile(os.path.join(williamsSaveFilePath, str(statement)))

        if todaysDate in str(statement):
            fileFound = True
            print("Todays file found - " + str(statement))

    if fileFound == True:
        return  True
    else:
        return False

def readFiles():
    if outlook_is_running() == False:
        print("Outlook started")
        os.startfile("outlook")
        time.sleep(5)
    else:
        print("Outlook already open")

    # Downloads Files for green acres
    while downloadGreenAcresStatements() == False:
        time.sleep(10)

    # Downloads Files for williams
    while downloadWilliamsStatements() == False:
        time.sleep(10)

    print("Files downloaded")

    connectGreenAcresDB()
    truncateGreenAcresTable()
    readGreenAcres()

    connectWilliamsDB()
    truncateWilliamsTable()
    readWilliams()

def connectGreenAcresDB():
    global mydb
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="green acres db"
    )

def connectWilliamsDB():
    global mydb, mycursor


    mycursor.close()
    mydb.close()

    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="williams db"
    )

def truncateGreenAcresTable():
    global mydb, mycursor
    sql = "TRUNCATE TABLE meterreads"
    mycursor = mydb.cursor()
    mycursor.execute(sql)
    mydb.commit()
    print("Table truncated")

def truncateWilliamsTable():
    global mydb, mycursor
    sql = "TRUNCATE TABLE meterreads"
    mycursor = mydb.cursor()
    mycursor.execute(sql)
    mydb.commit()
    print("Table truncated")

def readGreenAcres():
    files = glob.glob(greenAcresSaveFilePath)
    # loops through and grabs every file
    try:
        for f in os.listdir(greenAcresSaveFilePath):
            if "Green Acres_Daily_Usage (Copy)_" in f:
                print(f)

                # Read the CSV into a pandas data frame (df)
                #   With a df you can do many things
                #   most important: visualize data with Seaborn
                df = pd.read_csv(greenAcresSaveFilePath + f, sep=',')
                # print(df)
                date = f[31:41]
                for index, row in df.iterrows():
                    if not str(row['Meter_Read']) == 'nan' and not str(row['Unit']) == '1' and not str(row['Unit']) == 'Commercial':
                        unit = str(row['Unit'])

                        for i in range(13):
                            if unit == "3":
                                unit = '14/13'
                                break
                            if unit == "4":
                                unit = '7'
                                break
                            if unit == "5":
                                unit = '6'
                                break
                            if unit == "6":
                                unit = '9'
                                break
                            if unit == "7":
                                unit = '3'
                                break
                            if unit == "8":
                                unit = '1'
                                break
                            if unit == "9":
                                unit = '10'
                                break
                            if unit == "10":
                                unit = '4'
                                break
                            if unit == "11":
                                unit = '5'
                                break
                            if unit == "12":
                                unit = '11'
                                break
                            if unit == "13":
                                unit = '12'
                                break
                            if unit == "14":
                                unit = '8'
                                break

                        print('Green Acres: Meter Read: ' + str(row['Meter_Read']) + ' Unit: ' + unit + ' Date: ' + str(date))
                        greenAcresPostSQL(str(row['Meter_Read']), unit, str(date), 'ele')
                        # row['Start_Meter_Read'], row['c2']

    except Exception as e:
        print(e)

def readWilliams():
    files = glob.glob(williamsSaveFilePath)
    # loops through and grabs every file
    try:
        for f in os.listdir(williamsSaveFilePath):
            if "WILLIAMS_APTS_DCAP_" in f:
                print(f)

                # Read the CSV into a pandas data frame (df)
                #   With a df you can do many things
                #   most important: visualize data with Seaborn
                df = pd.read_csv(williamsSaveFilePath + f, sep=',')
                # print(df)
                date = f[19:29]
                for index, row in df.iterrows():
                    if not str(row['Reading']) == 'nan' and not str(row['Apt']) == '2ND FLR COMMUNITY RM ' and not str(row['Apt']) == '':
                        type = str(row['Units'])

                        if type == 'KWatt_Hours':
                            type = 'ele'
                        if type == 'Gallons':
                            type = 'wtr'

                        print('Williams: Meter Read: ' + str(row['Reading']) + ' Unit: ' + str(row['Apt']) + ' Date: ' + str(date))
                        williamsPostSQL(str(row['Reading']), str(row['Apt']), str(date), type)
                        # row['Start_Meter_Read'], row['c2']

    except Exception as e:
        print('this is an error: ' + str(e))

def greenAcresPostSQL(meterread,unit,date,type):
    global mydb, mycursor
    now = datetime.now()
    formatted_date = now.strftime('%Y-%m-%d %H:%M:%S')

    print(mydb)

    mycursor = mydb.cursor()

    sql = "INSERT INTO meterreads (meterread,unit,date,type) VALUES (%s,%s,%s,%s)"
    val = [meterread,unit,date,type]

    mycursor.execute(sql, val)

    mydb.commit()

    print(mycursor.rowcount, "was inserted.")
# test
def williamsPostSQL(meterread,unit,date,type):
    global mydb, mycursor
    now = datetime.now()
    formatted_date = now.strftime('%Y-%m-%d %H:%M:%S')

    print('test')

    mycursor = mydb.cursor()

    sql = "INSERT INTO meterreads (meterread,unit,date,type) VALUES (%s,%s,%s,%s)"
    val = [meterread,unit,date,type]

    mycursor.execute(sql, val)

    mydb.commit()

    print(mycursor.rowcount, "was inserted.")

readFiles()