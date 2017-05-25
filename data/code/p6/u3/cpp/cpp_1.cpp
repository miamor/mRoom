#include <stdio.h>
#include <stdlib.h>

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
int x[10000];
int main()
{
    int n,i;
    scanf("%d",&n);
    for(i=0;i<n;i++)
    {
        scanf("%d",&x[i]);
    }
    sx_chen(x,n);
  
    for(i=0;i<n;i++) printf("%d\n",x[i]);
	  return 0;
	
}
