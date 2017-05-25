#include <stdio.h>
#include <stdlib.h>
void sx_luachon(int x[],int n)
{
    int i,j,trg,min;
    for(i=0;i<n-1;i++)
    {
        min=i;
        for(j=i+1;j<n;j++)
        {
            if(x[j]<x[min]) min=j;
        }
        if(min!=i)
        {
            trg =x[min];
            x[min]=x[i];
            x[i]=trg;
        }
    }
}

int x[10000];
int main()
{
    int n,i;
    scanf("%d",&n);
    for(i=0;i<n;i++)
    {
        scanf("%d",&x[i]);
    }
    sx_luachon(x,n);
  
    for(i=0;i<n;i++) printf("%d\t",x[i]);
	  return 0;
	
}
