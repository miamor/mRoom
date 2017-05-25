#include <iostream>
using namespace std;

// Cho 2 số nguyên dương a và b. Hãy tìm ước chung lớn nhất của 2 số này.
// Input : 2 số a,b
// Output : Ước chung lớn nhất của 2 số a, b
int UCLN(int a, int b)
{
    while ( a != b)
    {
        if (a > b)
            a = a - b;
        else
            b = b - a;
    }

    return a; // or return b; a = b
}

int main()
{
    int a, b;
    cin >> a >> b;

    int result = UCLN(a, b);
    cout << result;
}
