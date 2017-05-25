#include<stdio.h>
#include <time.h>
// Your code goes here...
#include <iostream>
#include <stdio.h>

using namespace std;

int usc(int a, int b)
{
    while(a!=b)
    {
        if(a>b) a=a-b;
        else b=b-a;
    }
    return (a);
}
int main() {
clock_t tStart = clock();

    int m, n;
    scanf("%d%d",&m,&n);
    printf("%d", usc(m,n));
    
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
return 0;
}