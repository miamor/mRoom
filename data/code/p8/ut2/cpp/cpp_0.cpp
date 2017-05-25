// selection sort (different)
#include <iostream>
#include <stdio.h>

using namespace std;

    int a[10000],i,j,n;
int main()
{
    scanf("%d",&n);
    for(i=0;i<n;i++)
        scanf("%d",&a[i]);
    for(i=0;i<n-1;i++)
        for(j=i+1;j<n;j++)
        if(a[i]>a[j]) swap(a[i],a[j]);
    for(i=0;i<n;i++)
        printf("%d ",a[i]);
    return 0;
}
