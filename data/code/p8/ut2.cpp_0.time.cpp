#include<stdio.h>
#include <time.h>
// selection sort (different)
#include <iostream>
#include <stdio.h>

using namespace std;

    int a[10000],i,j,n;
int main() {
clock_t tStart = clock();

    scanf("%d",&n);
    for(i=0;i<n;i++)
        scanf("%d",&a[i]);
    for(i=0;i<n-1;i++)
        for(j=i+1;j<n;j++)
        if(a[i]>a[j]) swap(a[i],a[j]);
    for(i=0;i<n;i++)
        printf("%d ",a[i]);
    
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
return 0;
}