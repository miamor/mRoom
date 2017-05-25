#include <time.h>
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
int main() {
clock_t tStart = clock();

    int n,i;
    scanf("%d",&n);
    int x[n];
    for(i=0;i<n;i++)
    {
        scanf("%d",&x[i]);
    }
    sx_chen(x,n);
  
    for(i=0;i<n;i++) printf("%d\n",x[i]);
	  
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
return 0;
	
}