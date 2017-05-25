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
void sx_chen(int x[],int n)
{
   int i,j,trg;
   for(i=0;i<n;i++)
   {
       trg=x[i];
       j=i-1;
       while(j>=0&&x[j]>trg)
       {
           x[j+1]=x[j];
           x[j]=trg;
           j--;
       }

   }
}
void sx_noibot(int x[],int n)
{
    int i,j,trg;
    for(i=0;i<n-1;i++)
    {
        for(j=n-1;j>0;j--)
        {
            if(x[j]<x[j-1])
            {
                trg =x[j-1];
                x[j-1]=x[j];
                x[j]=trg;
            }
        }
    }
}
void sx_nhanh(int x[], int l, int r)
{
    int i, j, key, trg;
    i = l;
    j = r;
    key = x[r];
    do {
        while (x[i] < key) i++;
        while (key < x[j]) j--;
        if (i <= j)
            {
                trg = x[i];
                x[i] = x[j];
                x[j] = trg;
                i++;
                j--;
            }
    }
    while (i<=j);
    if (l < j)    sx_nhanh(x, l, j);
    if (i < r)    sx_nhanh(x, i, r);
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
    //sx_luachon(x,n)
    sx_chen(x,n);
    //sx_noibot(x,n);
    //sx_nhanh(x,0,n-1);
  
    for(i=0;i<n;i++) printf("%d\t",x[i]);
	  return 0;
	
}
