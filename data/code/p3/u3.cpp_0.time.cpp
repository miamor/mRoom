#include<time.h>
// Your code goes here...
#include <iostream>
using namespace std;

int main() {
clock_t tStart = clock();

    int a, b;
    cin>>a>>b;
    int d = a - b;
    cout<<d<<endl;
    
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
return 0;
}