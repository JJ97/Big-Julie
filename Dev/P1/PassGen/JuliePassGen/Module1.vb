Imports System.IO
Module Module1
    Sub Main()
        ' Load password seed list into bank variable
        Dim reader As StreamReader = New StreamReader("N:\Documents\Dev\P1\PassGen\JuliePassGen\passSeed.txt")
        Dim bank As String = reader.ReadLine
        ' Split each word in wordbank into seperate indices in wordbank()
        Dim wordbank() As String = bank.Split(",")
        Dim word(wordbank.Length - 1) As String
        reader.Close()
        ' Open output file to write passwords to
        Dim writer As StreamWriter = New StreamWriter("N:\Documents\Dev\P1\PassGen\JuliePassGen\passwords.txt")
        ' Assign n with number specified by user (number of passwords required)
        Dim n As Integer = Console.ReadLine
        ' Generate new random seed
        Randomize()
        ' Repeat for number of passwords requested
        For i = 0 To n - 1
            ' Assign currently generating password as empty string
            word(i) = ""
            ' Repeat 3 times
            For j = 0 To 2
                ' Randomly generate value of 1 or 0
                ' If value is 1
                If CInt(Math.Floor((2) * Rnd())) = 1 Then
                    ' Select random word from wordbank() and append to password
                    word(i) = word(i) & wordbank(CInt(Math.Floor((wordbank.Length + 1) * Rnd())))
                Else
                    ' Generate random integer from between 0 and 5 as k
                    ' Repeat k times
                    For k = 0 To CInt(Math.Floor((6) * Rnd()))
                        ' Append random digit to password
                        word(i) = word(i) & (CInt(Math.Floor((10) * Rnd())))
                    Next
                End If
            Next
            ' write new password to output file
            writer.WriteLine(word(i))
        Next
        ' close output file
        writer.Close()
    End Sub
End Module
